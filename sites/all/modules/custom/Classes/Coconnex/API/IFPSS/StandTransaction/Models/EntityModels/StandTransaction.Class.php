<?php
namespace Coconnex\API\IFPSS\StandTransaction\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;

Class StandTransaction extends Db{

    public $id;
    public $customer_id;
    public $stand_ref_id;
    public $external_ref_id;
    public $product_key;
    public $product_name;
    public $description;
    public $additional_info;
    public $quantity;
    public $rate;
    public $total;
    public $reserved_grace_minutes;
    public $signing_grace_minutes;
    public $pricing_data;
    public $status;
    public $previous_status;
    public $notes;
    public $reserved_on;
    public $reserved_by;
    public $contract_submitted_on;
    public $contract_submitted_by;
    public $cancelled_on;
    public $cancelled_by;
    public $contract_cancellation_requested_on;
    public $contract_cancellation_requested_by;
    public $transaction_reason;
    public $deleted;

    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_stand_transaction',
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
        $delete_arr['table'] = 'cnx_stand_transaction';
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

    public function update_status($status)
    {
        $arrParam = array();
        $arrParam['previous_status'] = $this->status;
        $arrParam['status'] = $status;
        $arrParam['id'] = $this->id;
        $this->save($arrParam);
    }

}