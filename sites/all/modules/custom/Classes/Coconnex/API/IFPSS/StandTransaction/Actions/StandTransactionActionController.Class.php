<?php
namespace Coconnex\API\IFPSS\StandTransaction\Actions;

use Coconnex\API\IFPSS\BookingHistory\Managers\BookingHistoryManager;
use Coconnex\API\IFPSS\Exhibitor\Managers\CNXExhibitorManager;
use Coconnex\API\IFPSS\StandTransaction\Managers\StandTransactionManager;
use Coconnex\API\IFPSS\StandTransaction\Models\RequestModels\StandTransactionRequestModel;
use Coconnex\API\IFPSS\WaitingList\Collection\WaitingList;
use Coconnex\API\IFPSS\WaitingList\WaitingListItem\Managers\WaitingListItemManager;
use Coconnex\DBFactory\Db;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Managers\APIOrderManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\ReserveManager;
use Coconnex\Utils\Config\Config;

Class StandTransactionActionController{
    public static $message;

    public function __construct()
    {

    }

    public static function do_action($action_key, $stand_transaction_id, $user, $data){
        switch (trim(strtoupper($action_key))) {
            case 'RVD_CANCEL':
                return self::cancel_reservation($stand_transaction_id, $user, $data);
                break;
            case 'CNT_REQ_CANCEL':
                return self::contract_request_cancellation($stand_transaction_id, $user, $data);
                break;
            case 'CNT_PDF_DNWD':
                return self::download_contract_pdf($stand_transaction_id, $user, $data);
                break;
            case 'CNT_SIGN':
                return self::sign_contract($stand_transaction_id, $user, $data);
                break;
            case 'WAITLIST_CANCEL':
                return self::cancel_waitlist($stand_transaction_id, $user, $data);
                break;
            case 'ORD_PDF_DNWD':
                return self::download_order_pdf($stand_transaction_id, $user, $data);
                break;
            case 'ORD_REQ_CANCEL':
                return self::request_order_cancellation($stand_transaction_id, $user, $data);
                break;
            default:

                break;
        }
    }

    public static function cancel_reservation($stand_transaction_id, $user, $data){

        $obj_config = new Config("d6");
        $is_waitinglist_active = $obj_config::getvar("IS_WAITINGLIST");
        // echo 'cancel reservation'; die;
        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
        $release_response = $stand_transaction_manager->release_transaction_stand($user->mail);

        // $release_response['status'] = true;
        if ($release_response->status == 1) {
            $response_msg = 'Reservation cancelled successfully.';
            $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
            $stand_transaction_obj = $stand_transaction_manager->get_stand_transaction();
            $stand_ref_id = $stand_transaction_obj->stand_ref_id;
            $stand_transaction_manager->cancel_transaction($data['updated_by'],$stand_ref_id);

            //ADD IN BOOKING HISTORY - 21-03-2024
            $action = "CANCELLED";
            $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
            $stand_transaction_manager->add_booking_history($action,$data['updated_by']);

            if($is_waitinglist_active == 1){
                $waitlist_mgr = new WaitingList($user->uid,'',$stand_ref_id);
                $waitlist_obj = $waitlist_mgr->get_first_waitlist_exhibitor();
                $first_waitlist_customer_id = $waitlist_obj->customer_id;
                // /debug($first_waitlist_customer_id);
                if($first_waitlist_customer_id != "" && $first_waitlist_customer_id > 0){
                        $waitlist_mgr = new WaitingList($user->uid,'',$stand_ref_id);
                        $first_waitlist_user_id = $waitlist_mgr->get_first_waitlist_user($first_waitlist_customer_id);
                        // debug($first_waitlist_user_id);
                        if($first_waitlist_user_id != "" && $first_waitlist_user_id > 0){
                            $exhibitor = new CNXExhibitorManager(0,$first_waitlist_user_id);
                            $exhibCompanyName = $exhibitor->exhibitor->company_name;

                            $exhib_arr = array("exhib_nid" => $first_waitlist_customer_id,"exhib_name" => $exhibCompanyName, "exhib_mail" => $user->mail);
                            $exhib_obj = (object)$exhib_arr;
                            // debug($exhib_arr);
                            $stand_arr = array(
                                            "stand_number" => $waitlist_obj->additional_info->stand_no,
                                            "stand_nid" => $waitlist_obj->stand_ref_id,
                                            "stand_area" => $waitlist_obj->additional_info->quantity,
                                            "stand_type" => $waitlist_obj->additional_info->product_key
                                        );

                            $stand_obj = (object)$stand_arr;
                            // debug($stand_arr);
                            $ReserveManager = new ReserveManager($stand_obj,$exhib_obj);
                            $reserve_response = json_decode($ReserveManager->reserve_response,true);
                            // $reserve_response['status'] = true;
                            if($reserve_response['status']){
                                // debug('reserve_response');
                                $user_info = user_load($first_waitlist_user_id);
                                $stand_transaction = new StandTransactionRequestModel();

                                $grace_period_config = $obj_config::get_grace_period_config();
                                $reserved_grace_minutes = $grace_period_config['RESERVED']['grace_period_time_in_minutes'];
                                // $reserved_grace_minutes = $obj_config::getvar("RESERVE_GRACE_MINUTES");

                                $stand_transaction->stand_transaction_id = '';
                                $stand_transaction->customer_id = $waitlist_obj->customer_id;
                                $stand_transaction->stand_ref_id = $waitlist_obj->stand_ref_id;
                                $stand_transaction->product_key = $waitlist_obj->additional_info->product_key;
                                $stand_transaction->product_name = $waitlist_obj->additional_info->product_name;
                                $stand_transaction->description = $waitlist_obj->additional_info->description;
                                $stand_transaction->additional_info = $stand_transaction_obj->additional_info;
                                $stand_transaction->quantity = $waitlist_obj->additional_info->quantity;
                                $stand_transaction->rate = $waitlist_obj->additional_info->rate;
                                $stand_transaction->total = $waitlist_obj->additional_info->total;
                                $stand_transaction->reserved_grace_minutes = $reserved_grace_minutes;
                                $stand_transaction->pricing_data = '';
                                $stand_transaction->status = 'RESERVED';
                                $stand_transaction->notes = '';
                                $stand_transaction->reserved_on = date('Y-m-d H:i:s');
                                $stand_transaction->reserved_by = $user_info->profile_first_name . ' ' . $user_info->profile_last_name;

                                // debug($stand_transaction,1);
                                $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction);
                                $stand_transaction_manager->save_stand_transaction();

                                //ADD IN BOOKING HISTORY - 21-03-2024

                                $action = "RESERVED";
                                $stand_transaction_id = $stand_transaction_manager->stand_transaction->id;
                                $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                                $stand_transaction_manager->add_booking_history($action,$stand_transaction->reserved_by);

                                $waiting_list_item_manager = new WaitingListItemManager($user->uid, $user->roles, $waitlist_obj->waiting_list_item_id);
                                $waiting_list_item_manager->remove();

                                $waitlist_mgr = new WaitingList($user->uid,'',$stand_ref_id);
                                $waitlist_mgr->update_waitinglist_item_sequence();
                            }else{
                                $msg = 'Waiting list user not found';
                            }
                        }
                }else{
                    $msg = 'Waiting list exhibitor not found';
                }
            }
            $response = array("response_action" => 'show_msg',"response_msg" => $response_msg);
            return json_encode($response);
        } else {
            $response_msg = $release_response->msg;
            $response = array("response_action" => 'show_msg',"response_msg" => $response_msg);
            return json_encode($response);
        }


    }

    public static function contract_request_cancellation($stand_transaction_id, $user, $data){

        $response_action = 'reload';
        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);

        $stand_transaction_manager->contract_cancel_request_transaction($data['updated_by'],$data['cancellation_reason']);

        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
        $stand_transaction = $stand_transaction_manager->get_stand_transaction();

        if($stand_transaction->status == 'CANCELLATION_REQUESTED'){
            self::order_request_cancellation($stand_transaction->external_ref_id,$data);

            //ADD IN BOOKING HISTORY - 21-03-2024
            $action = "CONTRACT_CANCELLATION_REQUESTED";
            $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
            $stand_transaction_manager->add_booking_history($action,$data['updated_by']);

            $response_msg = 'Contract cancel request successfully submitted.';
        }else{
            $response_msg = 'Error while request, Please try again later.';
        }

        $response = array("response_action" => $response_action,"response_msg" => $response_msg);
        return json_encode($response);
    }

    public static function download_contract_pdf($stand_transaction_id, $user, $data){
        // echo 'download_contract_pdf';die;
        $response_action = 'download';
        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
        $response = $stand_transaction_manager->get_contract_pdf();
        $response = json_decode($response);
        if($response->status){
            $response_msg = $response->data->pdf_path;
            $response_file = $response->data->file_name;
        }else{
            $response_msg =  $response->msg;
            $response_file = '';
        }
        $response = array("response_action" => $response_action,"response_msg" => $response_msg,"response_file" => $response_file);
        return json_encode($response);
    }

    public static function sign_contract($stand_transaction_id, $user, $data){
        // echo 'sign_contract';die;
        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
        $response = $stand_transaction_manager->get_contract_link();
        $response = json_decode($response);
        if($response->status){
            $response_action = 'redirect';
            $response_msg =  $response->data->order_link;
        }else{
            $response_action = 'show_msg';
            $response_msg =  $response->msg;
        }
        $response = array("response_action" => $response_action,"response_msg" => $response_msg);
        return json_encode($response);
    }

    public static function cancel_waitlist($waitlist_item_id, $user, $data){

        $response_action = 'show_msg';
        $waitlist_item_manager = new WaitingListItemManager($user->uid, $user->roles, $waitlist_item_id);
        $waitlist_item_manager->remove();

        $waitlist_item_manager = new WaitingListItemManager($user->uid, $user->roles, $waitlist_item_id);
        $waitlist_item = $waitlist_item_manager->get_waitinglist_item();

        if($waitlist_item->deleted == '1'){
            $response_msg = 'Stand removed from waiting list successfully.';
        }else{
            $response_msg = 'Error while executing this action, Please try again later.';
        }

        $response = array("response_action" => $response_action,"response_msg" => $response_msg);
        return json_encode($response);
    }

    public static function download_order_pdf($order_id, $user, $data){

        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, '');
        $stand_transaction_id = $stand_transaction_manager->get_transaction_id($order_id);

        if($stand_transaction_id > 0){
            $response_action = 'download';
            $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
            $response = $stand_transaction_manager->get_contract_pdf();
            $response = json_decode($response);
            // debug($response);
            if($response->status){
                $response_msg = $response->data->pdf_path;
                $response_file = $response->data->file_name;
            }else{
                $response_msg =  $response->msg;
                $response_file = '';
            }
            $response = array("response_action" => $response_action,"response_msg" => $response_msg,"response_file" => $response_file);
        }else{
            $response = array("response_action" => 'show_msg',"response_msg" => 'Invalid reference.');
        }

        return json_encode($response);
    }

    public static function request_order_cancellation($order_id,$user,$data){
        $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, '');
        $stand_transactions = $stand_transaction_manager->get_transactions_by_order_id($order_id);
        if ($stand_transactions) {
            foreach ($stand_transactions as $transaction) {
                $stand_transaction_id = $transaction['id'];
                self::contract_request_cancellation($stand_transaction_id, $user, $data);
            }
        }
        $response_action = 'reload';
        $response_msg =  'Cancellation request submitted successfully.';

        $response = array("response_action" => $response_action,"response_msg" => $response_msg);
        return json_encode($response);

    }

    public static function order_request_cancellation($order_id,$data){
        $order_data = array('order_id' => $order_id, 'reason' => $data['cancellation_reason']);
        $order_obj = (object)$order_data;

        $api_order_mgr = new APIOrderManager($order_obj,'order_cancel_request');
        $api_order_mgr->order_cancel_request();
        $response = $api_order_mgr->order_response;
        // debug($response);
        $response = json_decode($response);
        $response_action = 'show_msg';
        if($response->status){
            $response_msg =  'Cancellation request submitted successfully.';
        }else{
            $response_msg =  $response->msg;
        }
        $response = array("response_action" => $response_action,"response_msg" => $response_msg);
        return json_encode($response);
    }

}