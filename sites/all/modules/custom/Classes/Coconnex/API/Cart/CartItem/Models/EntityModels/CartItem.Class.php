<?php
namespace Coconnex\API\Cart\CartItem\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))  . "/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CartItem extends Db{

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

    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_cart_items',
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
        $delete_arr['table'] = 'cnx_cart_items';
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