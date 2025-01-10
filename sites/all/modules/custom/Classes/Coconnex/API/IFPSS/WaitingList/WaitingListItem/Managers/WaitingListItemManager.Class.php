<?php

namespace Coconnex\API\IFPSS\WaitingList\WaitingListItem\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/WaitinglistItemRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/ResponseModels/WaitinglistItemResponseModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/WaitinglistItem.Class.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/StandTransaction/Actions/Managers/StandTransactionActionsManager.Class.php");

use Coconnex\API\IFPSS\StandTransaction\Actions\Managers\StandTransactionActionsManager;
use Coconnex\API\IFPSS\WaitingList\WaitingListItem\Models\EntityModels\WaitinglistItem;
use Coconnex\API\IFPSS\WaitingList\WaitingListItem\Models\RequestModels\WaitinglistItemRequestModel;
use Coconnex\API\IFPSS\WaitingList\WaitingListItem\Models\ResponseModels\WaitinglistItemResponseModel;
use Coconnex\DBFactory\Db;

class WaitingListItemManager
{
    protected $uid;
    public $roles;
    public $waitinglist_item;
    public $actions;

    public function __construct($uid,$roles,$waitinglist_item)
    {
        $this->uid = $uid;
        if (is_array($roles)) $this->roles = $roles;
        if ($waitinglist_item instanceof WaitinglistItemRequestModel || $waitinglist_item instanceof WaitinglistItem) $this->waitinglist_item = $waitinglist_item;
        if (is_numeric($waitinglist_item)) $this->waitinglist_item = new WaitinglistItem($this->uid, $waitinglist_item);
        $this->set_action();
    }

    public function save_waitinglist_item()
    {
        if ($this->waitinglist_item instanceof WaitinglistItemRequestModel) {
            $entity = new WaitinglistItem($this->uid, $this->waitinglist_item->id);
            if (isset($this->waitinglist_item->id)) $entity->id  = $this->waitinglist_item->id;
            if (isset($this->waitinglist_item->customer_id)) $entity->customer_id  = $this->waitinglist_item->customer_id;
            if (isset($this->waitinglist_item->stand_ref_id)) $entity->stand_ref_id  = $this->waitinglist_item->stand_ref_id;
            if (isset($this->waitinglist_item->additional_info)) $entity->additional_info  = json_encode($this->waitinglist_item->additional_info);
            if (isset($this->waitinglist_item->sequence) && $this->waitinglist_item->sequence != null){
                $sequence = $this->waitinglist_item->sequence;
            }else{
                $max_sequence = $this->get_max_sequence();
                $sequence = $max_sequence + 1;
            }
            $entity->sequence = $sequence;
            $result = $entity->save();
            $waitinglist_item = null;
            if ($result > 0) {
                if (is_numeric($result)) {
                    $waitinglist_item = $result;
                } elseif (isset($this->waitinglist_item->id)) {
                    $waitinglist_item = $this->waitinglist_item->id;
                }
                $this->waitinglist_item = new WaitinglistItem($this->uid, $waitinglist_item);
            }
            return $result;
        }
    }

    function get_waitinglist_item()
    {
        if ($this->waitinglist_item instanceof WaitinglistItem) {
            $response = new WaitinglistItemResponseModel();
            $response->waiting_list_item_id = $this->waitinglist_item->id;
            $response->customer_id = $this->waitinglist_item->customer_id;
            $response->stand_ref_id = $this->waitinglist_item->stand_ref_id;
            $response->additional_info = json_decode($this->waitinglist_item->additional_info);
            $response->sequence = $this->waitinglist_item->sequence;
            $response->deleted = $this->waitinglist_item->deleted;
            $response->waiting_list_actions = $this->actions;
            return $response;
        }
    }

    protected function set_action(){
        $stand_transaction_action_mgnr = new StandTransactionActionsManager($this->waitinglist_item->id,$this->roles,'WAITLISTED');
        $this->actions = $stand_transaction_action_mgnr->get_actions();
    }

    function get_max_sequence(){
         $max_sequence = 0;
         $sql = "select
                max(sequence) AS sequence
                from cnx_waiting_list
                where stand_ref_id = ".$this->waitinglist_item->stand_ref_id."
                AND deleted = 0";
        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $max_sequence = $res['sequence'];
            }
        }
        return $max_sequence;
    }

    public function update_sequence($new_sequence){
        $this->waitinglist_item->sequence = $new_sequence;
        $this->waitinglist_item->save();
    }

    public function delete()
    {
        $this->waitinglist_item->delete();
    }

    public function remove()
    {
        $this->waitinglist_item->remove();
    }

    public function revoke()
    {
        $this->waitinglist_item->revoke();
    }
}
