<?php
namespace Coconnex\API\IFPSS\WaitingList\Collection;
use Coconnex\API\IFPSS\Exhibitor\Managers\CNXExhibitorManager;

include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/DBFactory/Db.Class.php");
require_once dirname(dirname(dirname(__FILE__)))."/Exhibitor/Managers/CNXExhibitorManager.Class.php";

use Coconnex\API\IFPSS\WaitingList\WaitingListItem\Managers\WaitingListItemManager;
use Coconnex\DBFactory\Db;

class WaitingList extends Db{

    protected $uid;
    protected $roles;
    protected $customer_id;
    protected $stand_ref_id;
    public $waiting_list = array();

    public function __construct($user_id,$roles = array(),$customer_id = 0,$stand_ref_id = ""){
        $this->uid = $user_id;
        if (is_array($roles)) $this->roles = $roles;
        $this->customer_id = $customer_id;
        $this->stand_ref_id = $stand_ref_id;
        if($this->customer_id != "") $this->get_list();
        if($this->stand_ref_id != "") $this->get_list_by_stand();
	}

    public function get_list(){
        if($this->customer_id == 0){
            $cnx_exhib_mgr = new CNXExhibitorManager(0,$this->uid);
            $this->customer_id = $cnx_exhib_mgr->exhibitor->external_ref_id;
        }

        $sql = "SELECT id
        FROM cnx_waiting_list
        WHERE customer_id = '$this->customer_id'
        AND deleted = 0 ";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $waiting_list_item_id = $res['id'];
                $waiting_list_item_manager = new WaitingListItemManager($this->uid,$this->roles,$waiting_list_item_id);
                $waiting_list_item = $waiting_list_item_manager->get_waitinglist_item();
                $this->waiting_list[] = $waiting_list_item;
            }
        }
    }

    public function get_list_by_stand(){
        $sql = "SELECT id
        FROM cnx_waiting_list
        WHERE stand_ref_id = ".$this->stand_ref_id."
        AND deleted = 0 ";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $waiting_list_item_id = $res['id'];
                $waiting_list_item_manager = new WaitingListItemManager($this->uid,$this->roles,$waiting_list_item_id);
                $waiting_list_item = $waiting_list_item_manager->get_waitinglist_item();
                $this->waiting_list[] = $waiting_list_item;
            }
        }
    }

    public function update_waitinglist_item_sequence(){
        $sql = "SELECT
                id,sequence
            FROM cnx_waiting_list
            WHERE stand_ref_id = ".$this->stand_ref_id."
            AND deleted = 0";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $waiting_list_item_id = $res['id'];
                $sequence = $res['sequence'];
                $updated_sequence = ($sequence > 1) ? $sequence - 1 : $sequence;
                $waiting_list_item_manager = new WaitingListItemManager($this->uid,$this->roles,$waiting_list_item_id);
                $waiting_list_item = $waiting_list_item_manager->update_sequence($updated_sequence);
            }
        }
    }

    public function get_first_waitlist_exhibitor(){
        $waiting_list_item = "";
        $sql = "SELECT
                id
            FROM cnx_waiting_list
            WHERE stand_ref_id = ".$this->stand_ref_id."
            AND sequence = 1
            AND deleted = 0";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $waiting_list_item_id = $res['id'];
                $waiting_list_item_manager = new WaitingListItemManager($this->uid,$this->roles,$waiting_list_item_id);
                $waiting_list_item = $waiting_list_item_manager->get_waitinglist_item();
            }
        }
        return $waiting_list_item;
    }

    public function get_first_waitlist_user($customer_id){

        $user_id = 0;

        $sql = "SELECT
                user_ref_id
            FROM cnx_exhibitor
            WHERE external_ref_id = ".$customer_id."
            AND enabled = 1";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $user_id = $res['user_ref_id'];
            }
        }

        return $user_id;
    }



}
