<?php
namespace Coconnex\API\IFPSS\CallbackHooks;

include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/DBFactory/Db.Class.php");

use Coconnex\API\IFPSS\StandTransaction\Managers\StandTransactionManager;
use Coconnex\API\IFPSS\StandTransaction\Models\RequestModels\StandTransactionRequestModel;
use Coconnex\DBFactory\Db;

class CallbackHooks
{
    public static function updateTransactionStatus($user,$updated_by,$data)
    {
        $sql = "SELECT id
                FROM cnx_stand_transaction
                WHERE
                external_ref_id = ".$data->order_id."
                AND STATUS IN ('CONTRACT_SUBMITTED','CONTRACT_ACCEPTED','CONTRACT_COMPLETED','CONTRACT_CANCELLATION_REQUESTED','CONTRACT_CANCELLATION_APPROVED')
                AND deleted=0
                ORDER BY id ASC";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $transaction) {
                $stand_transaction_id =  $transaction['id'];
                $status = strtoupper($data->status);
                if($status == 'CANCELLED'){
                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_obj = $stand_transaction_manager->get_stand_transaction();
                    // $release_response = $stand_transaction_manager->release_transaction_stand($user->mail);
                    // if ($release_response->status == 1) {
                        $stand_ref_id = $stand_transaction_obj->stand_ref_id;
                        $stand_transaction_manager->cancel_transaction($updated_by,$stand_ref_id);

                        //ADD IN BOOKING HISTORY - 21-03-2024
                        $action = "CANCELLED";
                        // watchdog('BNAME',$updated_by);
                        $stand_transaction_manager->add_booking_history($action,$updated_by);
                    // }

                }elseif($status == 'CONTRACT_SUBMITTED'){
                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_manager->contract_submit_transaction($updated_by,$data->order_id);

                    //ADD IN BOOKING HISTORY - 21-03-2024
                    $action = "CONTRACT_SUBMITTED";
                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_manager->add_booking_history($action,$updated_by);
                }else{
                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_manager->update_status($status);

                    //ADD IN BOOKING HISTORY - 21-03-2024
                    $action = ($status == 'CONTRACT_CANCELLATION_APPROVED') ? 'CANCELLATION_INITIATED' : $status;
                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_manager->add_booking_history($action,$updated_by);
                }
            }
        }
    }

    public static function sendWelcomeEmail($exhib_nid){
        $sql = "SELECT
                    user_ref_id
                FROM cnx_exhibitor
                WHERE
                external_ref_id = " . $exhib_nid . "
                AND enabled=1
                ORDER BY id ASC";
        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        if ($result) {
            foreach ($result as $res_array) {
                $id = $res_array['user_ref_id'];
                $response = sendWelcomeEmail($id);
                return $response;
            }
        }
    }

    public static function updateTransactionStatusbyStandnid($user,$data)
    {
        $sql = "SELECT id
                FROM cnx_stand_transaction
                WHERE
                stand_ref_id = ".$data->stand_nid."
                AND customer_id = ".$data->exhib_nid."
                AND STATUS = 'RESERVED'
                AND deleted=0
                ORDER BY id ASC";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        if ($result) {
            foreach ($result as $transaction) {
                $stand_transaction_id =  $transaction['id'];
                $status = strtoupper($data->status);
                if($status == 'CANCELLED'){
                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_obj = $stand_transaction_manager->get_stand_transaction();

                    $stand_ref_id = $stand_transaction_obj->stand_ref_id;
                    $stand_transaction_manager->cancel_transaction($data->updated_by,$stand_ref_id);

                    //ADD IN BOOKING HISTORY - 21-03-2024
                    $action = "CANCELLED";
                    $stand_transaction_manager->add_booking_history($action,$data->updated_by);

                    $stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
                    $stand_transaction_obj = $stand_transaction_manager->get_stand_transaction();

                    $status = 'NOTEXISTS';
                    $stand_ref_id = 0;
                    $success = 0;
                    $sql = "SELECT stand_ref_id FROM cnx_stands_taken WHERE stand_ref_id=%d AND status != 'CANCELLED' AND deleted = 0";
                    $query = db_query($sql, $stand_ref_id);
                    while ($row = db_fetch_array($query)) {
                        $stand_ref_id = $row['stand_ref_id'];
                    }
                    $status = ($stand_ref_id > 0) ? 'EXISTS' : $status;

                    if($stand_transaction_obj->status == 'CANCELLED' && $status == 'NOTEXISTS'){
                        $success = 1;
                    }
                    return ($success == 1) ? 'SUCCESS' : 'FAILURE';
                }
            }
        }
    }
}
