<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_SUPPLIERS_ENTITY_MODEL extends Db{

	public $id;
	public $external_ref_id;
	public $company_name;
	public $address_1;
	public $address_2;
	public $address_3;
	public $city;
	public $state;
	public $country;
	public $postal_code;
	public $active;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_suppliers','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}