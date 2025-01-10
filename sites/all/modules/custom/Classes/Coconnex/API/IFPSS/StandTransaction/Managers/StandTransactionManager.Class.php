<?php

namespace Coconnex\API\IFPSS\StandTransaction\Managers;

require_once(dirname(dirname(__FILE__)) . "/Actions/Managers/StandTransactionActionsManager.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/StandTransactionRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/ResponseModels/StandTransactionResponseModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/StandTransaction.Class.php");
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Integrations/Coconnex/BackendAPIClient/StandTransactions/Managers/ReleaseManager.Class.php");
require_once (dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Utils/Email/Emails.Class.php");

use Coconnex\API\IFPSS\BookingHistory\Managers\BookingHistoryManager;
use Coconnex\API\IFPSS\StandTransaction\Actions\Managers\StandTransactionActionsManager;
use Coconnex\API\IFPSS\StandTransaction\Models\EntityModels\StandTransaction;
use Coconnex\API\IFPSS\StandTransaction\Models\RequestModels\StandTransactionRequestModel;
use Coconnex\API\IFPSS\StandTransaction\Models\ResponseModels\StandTransactionResponseModel;
use Coconnex\DBFactory\Db;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Managers\APIOrderManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\ReleaseManager;
use Coconnex\Utils\Config\Config;
use Emails;

// extends TransactionMail
Class StandTransactionManager {

    public $uid;
    public $roles;
    public $stand_transaction;
    public $stand_transaction_actions;
    public $status_titles = array('CONTRACT_SUBMITTED' => 'CONTRACT_SIGNATURE_PENDING',
                                    'CONTRACT_ACCEPTED' => 'CONTRACT_SUBMITTED',
                                    'CONTRACT_CANCELLATION_REQUESTED' => 'CANCELLATION_REQUESTED',
                                    'CONTRACT_CANCELLATION_APPROVED' => 'CANCELLATION_INITIATED');

    public function __construct($uid,$roles,$stand_transaction)
    {
        $this->uid = $uid;
        if (is_array($roles)) $this->roles = $roles;
        if ($stand_transaction instanceof StandTransactionRequestModel || $stand_transaction instanceof StandTransaction) $this->stand_transaction = $stand_transaction;
        if (is_numeric($stand_transaction)) $this->stand_transaction = new StandTransaction($this->uid, $stand_transaction);
        $this->stand_transaction_actions = $this->set_action();
    }

    public function save_stand_transaction()
    {
        if ($this->stand_transaction instanceof StandTransactionRequestModel) {

            $stand_transaction_entity = new StandTransaction($this->uid, $this->stand_transaction->stand_transaction_id);

            if (isset($this->stand_transaction->stand_transaction_id))          $stand_transaction_entity->id  = $this->stand_transaction->stand_transaction_id;
            if (isset($this->stand_transaction->customer_id))                   $stand_transaction_entity->customer_id  = $this->stand_transaction->customer_id;
            if (isset($this->stand_transaction->stand_ref_id))                  $stand_transaction_entity->stand_ref_id  = $this->stand_transaction->stand_ref_id;
            if (isset($this->stand_transaction->external_ref_id))               $stand_transaction_entity->external_ref_id  = $this->stand_transaction->external_ref_id;
            if (isset($this->stand_transaction->product_key))                   $stand_transaction_entity->product_key  = $this->stand_transaction->product_key;
            if (isset($this->stand_transaction->product_name))                  $stand_transaction_entity->product_name  = $this->stand_transaction->product_name;
            if (isset($this->stand_transaction->description))                   $stand_transaction_entity->description  = $this->stand_transaction->description;
            if (isset($this->stand_transaction->additional_info))               $stand_transaction_entity->additional_info  = json_encode($this->stand_transaction->additional_info);
            if (isset($this->stand_transaction->quantity))                      $stand_transaction_entity->quantity  = $this->stand_transaction->quantity;
            if (isset($this->stand_transaction->rate))                          $stand_transaction_entity->rate  = $this->stand_transaction->rate;
            if (isset($this->stand_transaction->total))                         $stand_transaction_entity->total  = round($this->stand_transaction->total, 2);
            if (isset($this->stand_transaction->reserved_grace_minutes))        $stand_transaction_entity->reserved_grace_minutes  = $this->stand_transaction->reserved_grace_minutes;
            if (isset($this->stand_transaction->signing_grace_minutes))         $stand_transaction_entity->signing_grace_minutes  = $this->stand_transaction->signing_grace_minutes;
            if (isset($this->stand_transaction->pricing_data))                  $stand_transaction_entity->pricing_data  = $this->stand_transaction->pricing_data;
            if (isset($this->stand_transaction->status))                        $stand_transaction_entity->status  = $this->stand_transaction->status;
            if (isset($this->stand_transaction->previous_status))               $stand_transaction_entity->previous_status  = $this->stand_transaction->previous_status;
            if (isset($this->stand_transaction->notes))                         $stand_transaction_entity->notes  = $this->stand_transaction->notes;
            if (isset($this->stand_transaction->reserved_on))                   $stand_transaction_entity->reserved_on  = $this->stand_transaction->reserved_on;
            if (isset($this->stand_transaction->reserved_by))                   $stand_transaction_entity->reserved_by  = $this->stand_transaction->reserved_by;
            if (isset($this->stand_transaction->contract_submitted_on))         $stand_transaction_entity->contract_submitted_on  = $this->stand_transaction->contract_submitted_on;
            if (isset($this->stand_transaction->contract_submitted_by))         $stand_transaction_entity->contract_submitted_by  = $this->stand_transaction->contract_submitted_by;
            if (isset($this->stand_transaction->cancelled_on))                  $stand_transaction_entity->cancelled_on  = $this->stand_transaction->cancelled_on;
            if (isset($this->stand_transaction->cancelled_by))                          $stand_transaction_entity->cancelled_by  = $this->stand_transaction->cancelled_by;
            if (isset($this->stand_transaction->contract_cancellation_requested_on))    $stand_transaction_entity->contract_cancellation_requested_on  = $this->stand_transaction->contract_cancellation_requested_on;
            if (isset($this->stand_transaction->contract_cancellation_requested_by))    $stand_transaction_entity->contract_cancellation_requested_by  = $this->stand_transaction->contract_cancellation_requested_by;
            if (isset($this->stand_transaction->reason))                                $stand_transaction_entity->transaction_reason  = $this->stand_transaction->reason;

            $result = $stand_transaction_entity->save();
            $stand_transaction_id = null;
            if ($result > 0) {
                if (is_numeric($result)) {
                    $stand_transaction_id = $result;
                } elseif (isset($this->stand_transaction->stand_transaction_id)) {
                    $stand_transaction_id = $this->stand_transaction->stand_transaction_id;
                }
                $this->stand_transaction = new StandTransaction($this->uid, $stand_transaction_id);
            }
        }
    }

    function get_stand_transaction()
    {
        if ($this->stand_transaction instanceof StandTransaction) {
            $stand_transaction_response = new StandTransactionResponseModel();
            $stand_transaction_response->stand_transaction_id = $this->stand_transaction->id;
            $stand_transaction_response->customer_id = $this->stand_transaction->customer_id;
            $stand_transaction_response->stand_ref_id = $this->stand_transaction->stand_ref_id;
            $stand_transaction_response->external_ref_id = $this->stand_transaction->external_ref_id;
            $stand_transaction_response->product_key = $this->stand_transaction->product_key;
            $stand_transaction_response->product_name = $this->stand_transaction->product_name;
            $stand_transaction_response->description = $this->stand_transaction->description;
            $stand_transaction_response->additional_info = json_decode($this->stand_transaction->additional_info);
            $stand_transaction_response->quantity = $this->stand_transaction->quantity;
            $stand_transaction_response->rate = $this->stand_transaction->rate;
            $stand_transaction_response->total = $this->stand_transaction->total;
            $stand_transaction_response->reserved_grace_minutes = $this->stand_transaction->reserved_grace_minutes;
            $stand_transaction_response->signing_grace_minutes = $this->stand_transaction->signing_grace_minutes;
            $stand_transaction_response->pricing_data = $this->stand_transaction->pricing_data;
            $stand_transaction_response->status = (array_key_exists($this->stand_transaction->status,$this->status_titles)) ? $this->status_titles[$this->stand_transaction->status]: $this->stand_transaction->status;
            $stand_transaction_response->previous_status = $this->stand_transaction->previous_status;
            $stand_transaction_response->status_title = (array_key_exists($this->stand_transaction->status,$this->status_titles)) ? $this->status_titles[$this->stand_transaction->status]: $this->stand_transaction->status;
            $stand_transaction_response->notes = $this->stand_transaction->notes;
            $stand_transaction_response->reserved_on = $this->stand_transaction->reserved_on;
            $stand_transaction_response->reserved_by = $this->stand_transaction->reserved_by;
            $stand_transaction_response->contract_submitted_on  = $this->stand_transaction->contract_submitted_on;
            $stand_transaction_response->contract_submitted_by  = $this->stand_transaction->contract_submitted_by;
            $stand_transaction_response->cancelled_on = $this->stand_transaction->cancelled_on;
            $stand_transaction_response->cancelled_by = $this->stand_transaction->cancelled_by;
            $stand_transaction_response->contract_cancellation_requested_on = $this->stand_transaction->contract_cancellation_requested_on;
            $stand_transaction_response->contract_cancellation_requested_by = $this->stand_transaction->contract_cancellation_requested_by;
            $stand_transaction_response->reason  = $this->stand_transaction->transaction_reason;
            $stand_transaction_response->transaction_actions = $this->stand_transaction_actions;
            return $stand_transaction_response;
        }
    }

    protected function set_action(){
        $stand_transaction_action_mgnr = new StandTransactionActionsManager($this->stand_transaction->id,$this->roles,$this->stand_transaction->status);
        return $stand_transaction_action_mgnr->get_actions();
    }

    public function delete()
    {
        $this->stand_transaction->delete();
    }

    public function remove()
    {
        $this->stand_transaction->remove();
    }

    public function revoke()
    {
        $this->stand_transaction->revoke();
    }

    public function update_status($status)
    {
        $this->stand_transaction->update_status($status);
    }

    public function cancel_transaction($cancelled_by,$stand_ref_id)
    {
        $this->stand_transaction->previous_status = $this->stand_transaction->status;
        $this->stand_transaction->status = 'CANCELLED';
        $this->stand_transaction->cancelled_on = date('Y-m-d H:i:s');
        $this->stand_transaction->cancelled_by = $cancelled_by;
        $this->stand_transaction->save();

        $delete_arr = array();
        $delete_arr['table'] = 'cnx_stands_taken';
        $delete_arr['conditions'] = ' stand_ref_id = ' . $stand_ref_id;
        $db = new Db();
        $db->DeleteRecordWithArr($delete_arr);
    }

    public function contract_submit_transaction($submitted_by,$order_id)
    {
        $this->stand_transaction->previous_status = $this->stand_transaction->status;
        $this->stand_transaction->status = 'CONTRACT_SUBMITTED';
        $this->stand_transaction->external_ref_id = $order_id;
        $this->stand_transaction->contract_submitted_on = date('Y-m-d H:i:s');
        $this->stand_transaction->contract_submitted_by = $submitted_by;
        $this->stand_transaction->save();
    }

    public function contract_cancel_request_transaction($contract_cancel_request_by,$contract_cancel_reason)
    {
        // debug($contract_cancel_reason);
        $this->stand_transaction->previous_status = $this->stand_transaction->status;
        $this->stand_transaction->status = 'CONTRACT_CANCELLATION_REQUESTED';
        $this->stand_transaction->transaction_reason = $contract_cancel_reason;
        $this->stand_transaction->contract_cancellation_requested_on = date('Y-m-d H:i:s');
        $this->stand_transaction->contract_cancellation_requested_by = $contract_cancel_request_by;
        // debug($this->stand_transaction,1);
        $this->stand_transaction->save();
    }

    public function release_transaction_stand($exhib_mail) {
        unset($_SESSION['api_call_data']);
        $token = '';
        $additional_info = json_decode($this->stand_transaction->additional_info);
        $stand_arr = array(
            "stand_number" => $additional_info->stand_no,
            "stand_status" => $this->stand_transaction->product_key,
            "stand_nid" => $this->stand_transaction->stand_ref_id
        );

        $stand_obj = (object)$stand_arr;

        $release_mgr = new ReleaseManager($stand_obj, $token, $exhib_mail);
        $release_response = json_decode($release_mgr->release_response);
        // watchdog('LOG:',print_r($release_response,true));
        // watchdog('LOG:',print_r($this->stand_transaction,true));
        if($this->stand_transaction->status == 'CONTRACT_SUBMITTED' && $release_response->status == 1){
            $this->order_cancellation();
        }

        return $release_response;
    }

    public function order_cancellation(){
        $order_data = array('order_id' => $this->stand_transaction->external_ref_id);
        $order_obj = (object)$order_data;
        // watchdog('LOG:',print_r($order_data,true));
        $api_order_mgr = new APIOrderManager($order_obj,'order_cancel');
        $api_order_mgr->order_cancel();
        $response = $api_order_mgr->order_response;
        // watchdog('LOG:',print_r($response,true));
        // debug($response);
        return $response;
    }

    public function get_contract_pdf(){
        $api_order_mgr = new APIOrderManager($this->stand_transaction->external_ref_id,'order_pdf');
        $api_order_mgr->get_order_pdf_path();
        return $api_order_mgr->order_response;
    }

    public function get_contract_link(){
        $api_order_mgr = new APIOrderManager($this->stand_transaction->external_ref_id,'order_link');
        $api_order_mgr->get_order_link();
        return $api_order_mgr->order_response;
    }

    public function get_transaction_id($order_id){
        $transaction_id = 0;

        $sql = "select id
        from cnx_stand_transaction
        where external_ref_id = " . $order_id .
        " AND deleted = 0
        AND status IN ('CONTRACT_SUBMITTED','CONTRACT_ACCEPTED','CONTRACT_COMPLETED')
        ORDER BY id DESC
        LIMIT 0,1";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        if ($result) {
            $i = 0;
            foreach ($result as $res) {
                $transaction_id = $res['id'];
            }
        }
        return $transaction_id;
    }

    public function get_transactions_by_order_id($order_id){
        $sql = "select id
        from cnx_stand_transaction
        where external_ref_id = " . $order_id .
        " AND deleted = 0
        AND status IN ('CONTRACT_SUBMITTED','CONTRACT_ACCEPTED','CONTRACT_COMPLETED')
        ORDER BY id DESC
        LIMIT 0,1";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        return $result;
    }

    public function add_booking_history($action,$updated_by){

        //ADD IN BOOKING HISTORY - 21-03-2024
        $stand_transaction_ref = $this->stand_transaction->id;
        $additional_info = json_decode($this->stand_transaction->additional_info);
        $standno = $additional_info->stand_no;

        $booking_history_item_obj = '';
        $booking_history_manager = new BookingHistoryManager($this->uid, $booking_history_item_obj);
        $booking_history_item_obj = $booking_history_manager->get_bookinghistory_request_model($action,$stand_transaction_ref,$standno,$updated_by);

        $booking_history_manager = new BookingHistoryManager($this->uid, $booking_history_item_obj);
        $booking_history_manager->save_bookinghistory();
    }

    public function get_transaction_elapse_time($status,$type = 'MINUTES'){
        $current_date = date('Y-m-d H:i:s');

        $reserved_on = ($status == 'RESERVED') ? $this->stand_transaction->reserved_on : $this->stand_transaction->contract_submitted_on;

        $dateTimeObject1 = date_create($reserved_on);
        $dateTimeObject2 = date_create($current_date);

        $interval = date_diff($dateTimeObject1, $dateTimeObject2);
        // debug($interval,1);
        $elapse_time_in_min = $interval->days * 24 * 60;
        $elapse_time_in_min += $interval->h * 60;
        $elapse_time_in_min += $interval->i;

        return ($type = 'ARRAY') ? $interval : $elapse_time_in_min;
    }

    public function get_transaction_remaining_time($status,$type = 'MINUTES'){
        $current_date = date('Y-m-d H:i:s');

        $reserved_on = ($status == 'RESERVED') ? $this->stand_transaction->reserved_on : $this->stand_transaction->contract_submitted_on;
        // debug($reserved_on);
        $grace_minutes = ($status == 'RESERVED') ? $this->stand_transaction->reserved_grace_minutes : $this->stand_transaction->signing_grace_minutes;
        //  debug($grace_minutes);
        //  debug(strtotime(' + '.$grace_minutes.' min ', strtotime($reserved_on)));
        //  debug(date_create(date("Y-m-d H:i:s", strtotime(' + '.$grace_minutes.' min ', strtotime($reserved_on)))));
        // $ExpiryDate = date('Y-m-d H:i:s',strtotime(' + '.$grace_minutes.' min ', strtotime($reserved_on)));
        //date("Y-m-d H:i:s", strtotime($expiry_time));
        // debug($current_date);
        // debug($ExpiryDate);
        if((date("Y-m-d H:i:s", strtotime(' + '.$grace_minutes.' min ', strtotime($reserved_on))) < date('Y-m-d H:i:s'))){
            $interval = array();
            $elapse_time_in_min = 0;
        }else{
            $dateTimeObject1 = date_create(date("Y-m-d H:i:s", strtotime(' + '.$grace_minutes.' min ', strtotime($reserved_on))));
            $dateTimeObject2 = date_create($current_date);
            // debug($dateTimeObject1);
            // debug($dateTimeObject2);
            $interval = date_diff($dateTimeObject1, $dateTimeObject2);

            $elapse_time_in_min = $interval->days * 24 * 60;
            $elapse_time_in_min += $interval->h * 60;
            $elapse_time_in_min += $interval->i;
        }
        // debug($interval,1);

        return ($type = 'ARRAY') ? $interval : $elapse_time_in_min;
    }

    public function send_reservation_cancellation_notification(){

        $emails = new Emails();
        // debug($emails);

        $main_hrs = $this->stand_transaction->reserved_grace_minutes / 60;
        $expiry_time = $main_hrs .' hours';

        $additional_info = json_decode($this->stand_transaction->additional_info);
        $standno = $additional_info->stand_no;

        $tokens = array(
            'NAME' => $this->stand_transaction->reserved_by,
            'STATUS' => $this->stand_transaction->status,
            'STANDNO' => $standno,
            'EXPIRY_TIME' => $expiry_time
        );
        // debug($tokens);
        $emails->setEmailType('RVDAUTOCNL');

        $emails->applyTokens($tokens);

        $primary_mail = $this->get_exhibitor_mail($this->stand_transaction->customer_id);;
        // debug($primary_mail,1);

        if ($primary_mail) {
            $emails->setTo($primary_mail);
        }

        if (isset($primary_mail)) {
            $result = $emails->send();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function get_exhibitor_mail($customer_id){
        $mail = '';

        $sql = "select u.mail
                from cnx_exhibitor cxex
                join users u ON cxex.user_ref_id = u.uid
                where cxex.external_ref_id = " . $customer_id;

        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        if ($result) {
            $i = 0;
            foreach ($result as $res) {
                $mail = $res['mail'];
            }
        }
        return $mail;
    }

}