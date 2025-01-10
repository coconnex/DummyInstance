<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_BOOKING_HISTORY_ENTITY_MODEL extends Db{

	public $id;
	public $stand_transaction_ref;
	public $action;
	public $description;
	public $is_active;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_booking_history','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}