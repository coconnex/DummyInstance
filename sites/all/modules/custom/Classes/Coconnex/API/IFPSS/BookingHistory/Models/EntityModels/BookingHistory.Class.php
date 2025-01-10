<?php

namespace Coconnex\API\IFPSS\BookingHistory\Models\EntityModels;

use Coconnex\DBFactory\Db;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/DBFactory/Db.Class.php");



class BookingHistory extends Db
{
    public $id;
    public $stand_transaction_ref;
    public $action;
    public $description;
    public $created_by_name;
    public $modified_by_name;


    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_booking_history',
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
        $delete_arr['table'] = 'cnx_booking_history';
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
