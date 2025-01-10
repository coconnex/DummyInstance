<?php

namespace Coconnex\API\IFPSS\WaitingList\WaitingListItem\Models\EntityModels;
include_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . "/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;

class WaitinglistItem extends Db
{
    public $id;
    public $stand_ref_id;
    public $customer_id;
    public $additional_info;
    public $sequence;
    public $deleted;

    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_waiting_list',
            'id',
            'created_on',
            'created_by',
            'modified_on',
            'modified_by'
        );
        parent::__construct($id);
    }


    public function delete()
    {
        $delete_arr = array();
        $delete_arr['table'] = 'cnx_waiting_list';
        $delete_arr['conditions'] = ' id = ' . $this->id;
        $this->DeleteRecord($delete_arr);
    }

    public function remove()
    {
        $this->RemoveRecord($this->id);
    }

    public function revoke()
    {
        $this->RevokeRecord($this->id);
    }
}
