<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_CART_ITEMS_ENTITY_MODEL extends Db{

	public $id;
	public $cart_id;
	public $inventory_id;
	public $product_key;
	public $product_name;
	public $description;
	public $additional_info;
	public $quantity;
	public $rate;
	public $total;
	public $status;
	public $item_group_key;
	public $deleted;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_cart_items','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}