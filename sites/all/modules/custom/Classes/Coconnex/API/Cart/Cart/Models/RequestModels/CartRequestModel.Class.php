<?php
namespace Coconnex\API\Cart\Cart\Models\RequestModels;

Class CartRequestModel{
    public $cart_id;
    public $customer_id;
    public $user_id;
    public $item_count;
    public $total;
    public $currency;
    public $storefront_key;
    public $cart_items  = array();
}