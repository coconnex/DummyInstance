<?php
namespace Coconnex\API\Cart\Cart\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;

Class Cart extends Db{

    public $id;
    public $customer_id;
    public $user_id;
    public $item_count;
    public $total;
    public $currency;
    public $storefront_key;
    public $deleted;


    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_cart',
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
        $delete_arr['table'] = 'cnx_cart';
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