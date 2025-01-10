<?php

namespace Coconnex\API\IFPSS\BookingHistory\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/BookingHistoryRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/ResponseModels/BookingHistoryResponseModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/BookingHistory.Class.php");


use Coconnex\API\IFPSS\BookingHistory\Models\EntityModels\BookingHistory;
use Coconnex\API\IFPSS\BookingHistory\Models\RequestModels\BookingHistoryRequestModel;
use Coconnex\API\IFPSS\BookingHistory\Models\ResponseModels\BookingHistoryResponseModel;
use Coconnex\DBFactory\Db;

class BookingHistoryManager
{
    protected $uid;
    public $bookinghistory;
    const CON_INI_FILE = "config/description_settings.ini";

    public function __construct($uid,$bookinghistory)
    {
        $this->uid = $uid;

        if ($bookinghistory instanceof BookingHistoryRequestModel || $bookinghistory instanceof BookingHistory) $this->bookinghistory = $bookinghistory;
        if (is_numeric($bookinghistory)) $this->bookinghistory = new BookingHistory($this->uid, $bookinghistory);

    }

    public function save_bookinghistory()
    {

        if ($this->bookinghistory instanceof BookingHistoryRequestModel) {

            $entity = new BookingHistory($this->uid, $this->bookinghistory->id);
            if (isset($this->bookinghistory->id)) $entity->id  = $this->bookinghistory->id;
            if (isset($this->bookinghistory->stand_transaction_ref)) $entity->stand_transaction_ref  = $this->bookinghistory->stand_transaction_ref;
            if (isset($this->bookinghistory->action)) $entity->action  = $this->bookinghistory->action;
            if (isset($this->bookinghistory->description)) $entity->description  = $this->bookinghistory->description;
            if (isset($this->bookinghistory->created_by_name)) $entity->created_by_name  = $this->bookinghistory->created_by_name;
            if (isset($this->bookinghistory->modified_by_name)) $entity->modified_by_name  = $this->bookinghistory->modified_by_name;

            $result = $entity->save();
            $bookinghistory = null;
            if ($result > 0) {
                if (is_numeric($result)) {
                    $bookinghistory = $result;
                } elseif (isset($this->bookinghistory->id)) {
                    $bookinghistory = $this->bookinghistory->id;
                }
                $this->bookinghistory = new BookingHistory($this->uid, $bookinghistory);
            }
            return $result;
        }
    }

    function get_bookinghistory()
    {
        if ($this->bookinghistory instanceof BookingHistory) {
            $response = new BookingHistoryResponseModel();
            $response->booking_history_item_id = $this->bookinghistory->id;
            $response->stand_transaction_ref = $this->bookinghistory->stand_transaction_ref;
            $response->action = $this->bookinghistory->action;
            $response->description = $this->bookinghistory->description;
            $response->created_by_name = $this->bookinghistory->created_by_name;
            $response->modified_by_name = $this->bookinghistory->modified_by_name;


            return $response;
        }
    }
    function get_bookinghistory_request_model($action,$stand_transaction_ref,$standno,$updated_by){
        // echo $action;
        // echo'</br>';
        // echo $stand_transaction_ref;
        // echo '</br>';
        // echo $standno;
        // exit;
        $con_ini_file_path = dirname(dirname(__FILE__)). "/" . self::CON_INI_FILE;

        if(file_exists($con_ini_file_path)) $connectionSettings = parse_ini_file($con_ini_file_path,true);

        $booking_history_item_obj = new BookingHistoryRequestModel();

        $booking_history_item_obj->booking_history_item_id = '';
        $booking_history_item_obj->action =$action;
        $booking_history_item_obj->stand_transaction_ref = $stand_transaction_ref;

        $standID = $standno;
        // Check if the action key exists in the settings array

        $arrDescription = $connectionSettings["DESCRIPTION_SETTINGS"];

        if (array_key_exists($booking_history_item_obj->action, $arrDescription) )
        {
           // $actionDescription =$arrDescription[$booking_history_item_obj->action] ;
            $actionDescription = str_replace("Stand no", "Stand $standID", $arrDescription[$booking_history_item_obj->action]);

        }
        $booking_history_item_obj->description = $actionDescription;
        $booking_history_item_obj->created_by_name = $updated_by;
        $booking_history_item_obj->modified_by_name = $updated_by;
        // watchdog('BOBJ',print_r($booking_history_item_obj,true));
        return $booking_history_item_obj;


    }
    public function delete()
    {
        $this->bookinghistory->delete();
    }

    public function remove()
    {
        $this->bookinghistory->remove();
    }

    public function revoke()
    {
        $this->bookinghistory->revoke();
    }
}
