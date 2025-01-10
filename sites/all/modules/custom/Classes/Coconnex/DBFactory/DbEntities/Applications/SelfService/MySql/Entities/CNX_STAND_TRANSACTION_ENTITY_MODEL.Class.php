<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_STAND_TRANSACTION_ENTITY_MODEL extends Db{

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
	public $pricing_data;
	public $status;
	public $notes;
	public $deleted;
	public $reserved_grace_minutes;
	public $reserved_on;
	public $reserved_by;
	public $signing_grace_minutes;
	public $contract_submitted_on;
	public $contract_submitted_by;
	public $cancelled_on;
	public $cancelled_by;
	public $contract_cancellation_requested_on;
	public $contract_cancellation_requested_by;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_stand_transaction','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}