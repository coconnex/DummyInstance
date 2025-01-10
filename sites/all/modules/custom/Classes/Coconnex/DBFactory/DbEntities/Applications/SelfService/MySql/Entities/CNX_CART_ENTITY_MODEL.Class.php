<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_CART_ENTITY_MODEL extends Db{

	public $id;
	public $customer_id;
	public $user_id;
	public $item_count;
	public $total;
	public $currency;
	public $storefront_key;
	public $deleted;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_cart','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}