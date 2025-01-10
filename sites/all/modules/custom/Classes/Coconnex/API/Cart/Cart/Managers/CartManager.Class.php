<?php

namespace Coconnex\API\Cart\Cart\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/Cart.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/CartRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/ResponseModels/CartResponseModel.Class.php");

use Coconnex\API\Cart\Cart\Models\EntityModels\Cart;
use Coconnex\API\Cart\Cart\Models\RequestModels\CartRequestModel;
use Coconnex\API\Cart\Cart\Models\ResponseModels\CartResponseModel;
use Coconnex\API\Cart\CartItem\Managers\CartItemManager;
use Coconnex\API\Cart\CartItem\Models\EntityModels\CartItem;
use Coconnex\API\Cart\CartItem\Models\RequestModels\CartItemRequestModel;
use Coconnex\API\Cart\CartItem\Models\ResponseModels\CartItemResponseModel;
use Coconnex\DBFactory\Db;

Class CartManager{

    public $storefront_key;
    public $uid;
    public $customer_id;
    public $cart;
    public $items = array();

    public function __construct($storefront_key,$customer_id,$uid,$cart = null)
    {
        $this->storefront_key = $storefront_key;
        $this->uid = $uid;
        $this->customer_id = $customer_id;

        if ($cart == null && is_numeric($this->uid) && $this->uid > 0){
            $cart_id = $this->check_user_cart();
            if ($cart_id > 0) $this->cart = new Cart($this->uid, $cart_id);
            $this->set_entity_cart_items();
        }

        if ($cart instanceof CartRequestModel || $cart instanceof Cart) $this->cart = $cart;

        if ($this->cart instanceof CartRequestModel && $this->cart->cart_id == ""){
            if(isset($this->uid) && $this->uid > 0){
                $cart_id = $this->check_user_cart();
                $cart_item_count = $this->check_user_cart_items();
                if($cart_item_count > 0){
                    $this->merge_cart();
                }else{
                    $save = true;
                    if($cart_id > 0) $this->cart->cart_id = $cart_id;
                    $this->save_cart($save);
                }
            }else{
                $save = false;
                $this->save_cart($save);
            }
        }

        if (is_numeric($cart)){
            $this->cart = new Cart($this->uid, $cart);
            $this->set_entity_cart_items();
        }
    }

    public function get_entity()
    {
        return $this->cart;
    }

    protected function check_user_cart(){
        $cart_id = 0;
        $sql = "SELECT * FROM cnx_cart
                WHERE user_id = $this->uid
                AND storefront_key = 'STANDS'
                AND deleted = 0";
        $db = new Db();
        $cart_data = $db->RetrieveRecord($sql);

        if ($cart_data) {
            foreach ($cart_data as $cartdata) {
                $cart_id = $cartdata['id'];
            }
        }else{
            $cart_id = $this->create_user_cart();
        }
        return $cart_id;
    }

    protected function create_user_cart(){
        $cart_entity = new Cart($this->uid,'');

        $cart_entity->user_id = $this->uid;
        $cart_entity->customer_id = $this->customer_id;
        $cart_entity->item_count = 0;
        $cart_entity->storefront_key = $this->storefront_key;

        $cart_id = $cart_entity->save();
        return $cart_id;
    }

    protected function check_user_cart_items(){
        $item_count = 0;
        $sql = "SELECT c.id,cart_id,ci.id AS cart_item_id
                FROM cnx_cart c
                INNER JOIN cnx_cart_items ci ON ci.cart_id = c.id
                AND ci.deleted = 0
                AND ci.status = 'ADDED'
                WHERE c.user_id = $this->uid
                AND c.storefront_key = 'STANDS'
                AND c.deleted = 0";
        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            $item_count = sizeof($result);
            foreach ($result as $item) {
                $cart_id =  $item['cart_id'];
                $cart_item_id = $item['cart_item_id'];
                $cart_item_mgr = new CartItemManager($this->uid,$cart_item_id);
                $cart_item = $cart_item_mgr->get_entity();
                if ($this->cart instanceof CartRequestModel) {
                    if($this->cart->cart_id == "" || $this->cart->cart_id != $cart_id) $this->cart->cart_id = $cart_id;
                }
                $this->items[] = $cart_item;
            }
        }
        return $item_count;
    }

    public function save_cart($save = false)
    {
        if ($this->cart instanceof CartRequestModel) {

            $cart_items = $this->cart->cart_items; //Getting cart items before saving and converting type to cart entity

            $cart_entity = new Cart($this->uid, $this->cart->cart_id);
            if (isset($this->cart->cart_id)) $cart_entity->id = $this->cart->cart_id;
            if (isset($this->cart->user_id)) $cart_entity->user_id = $this->cart->user_id;
            if (isset($this->cart->customer_id)) $cart_entity->customer_id = $this->cart->customer_id;
            if (isset($this->cart->item_count)) $cart_entity->item_count = $this->cart->item_count;
            if (isset($this->cart->currency)) $cart_entity->currency = $this->cart->currency;
            if (isset($this->cart->storefront_key)) $cart_entity->storefront_key = $this->cart->storefront_key;
            $this->set_cart_totals($cart_items);
            $this->cart->item_count = sizeof($this->items);
            // debug($cart_entity);
            if ($save) {
                // unset($cart_entity->cart_items);
                $result = $cart_entity->save();
                $cart_id = null;
                if ($result > 0) {
                    if (is_numeric($result)) {
                        $cart_id = $result;
                    } elseif (isset($this->cart->cart_id)) {
                        $cart_id = $this->cart->cart_id;
                    }
                    $this->cart = new Cart($this->uid, $cart_id);
                } else {
                    return false;
                }
                if ($cart_id > 0 && is_array($cart_items)) {
                    $this->save_cart_items($cart_items,$save);
                }
            } else {
                $this->cart = $cart_entity;
                if (is_array($cart_items)) {
                    $this->save_cart_items($cart_items,$save);
                }
            }
            return $this;
        }
    }

    public function save_cart_items($cart_items = null,$save = false)
    {
        if (is_array($cart_items) && $this->cart instanceof Cart) {
            if (!$save)  $this->clear_virtual_cart_items();
            for ($i = 0; $i < sizeof($cart_items); $i++) {
                if ($cart_items[$i] instanceof CartItemRequestModel) {
                    if ($save) {
                        $cart_item_mgr = new CartItemManager($this->uid,$cart_items[$i]);
                        $result = $cart_item_mgr->save_cart_item($this->cart->id,$save);
                        if ($result > 0) {
                            $cart_item = $cart_item_mgr->get_entity();
                            if ($cart_item instanceof CartItem) {
                                // $this->items[] = $cart_item;
                                $this->set_entity_cart_items();
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        $this->save_cart_item_virtual($cart_items[$i]);
                    }
                } else {
                    return false;
                }
            }

            $this->set_cart_totals($this->items);
            $this->cart->item_count = sizeof($this->items);
            if ($save) $this->cart->save();
            return true;
        }
        return false;
    }

    protected function clear_virtual_cart_items()
    {
        for ($i = 0; $i <= sizeof($this->items); $i++) {
            if (!\is_numeric($this->items[$i]->id)) unset($this->items[$i]);
        }
        $this->items = array_values($this->items);
    }

    protected function save_cart_item_virtual(CartItemRequestModel $cart_item)
    {
        $item_idx = $this->get_cart_item_virtual($cart_item->cart_item_id);
        $item_mgr = new CartItemManager($this->uid,$cart_item);
        $item_mgr->save_cart_item($this->cart->id);

        if ($item_idx !== false) {
            $this->items[$item_idx] = $item_mgr->get_entity();
        } else {
            // $item_length = sizeof($this->items);
            // $item_srid = $item_length + 1;
            // $this->items['V'.$item_srid] = $item_mgr->get_entity();
            \array_push($this->items, $item_mgr->get_entity());
        }
        $item_mgr = null;
    }

    protected function get_cart_item_virtual($cart_item_id)
    {
        if (!\is_numeric($cart_item_id)) return false;
        for ($i = 0; $i < sizeof($this->items); $i++) {
            if ($this->items[$i]->id === $cart_item_id) return $i;
        }
        return false;
    }

    public function get_cart_items_virtual()
    {
        return $this->items;
    }

    public function set_entity_cart_items()
    {
        $sql = "select
                id
                from cnx_cart_items
                where cart_id = ".$this->cart->id."
                AND deleted = 0 AND status = 'ADDED'";
        $db = new Db();
        $cart_item_data = $db->RetrieveRecord($sql);

        if ($cart_item_data) {
            $this->items = [];
            foreach ($cart_item_data as $item) {
                $cart_item_id = $item['id'];
                $cart_item_mgr = new CartItemManager($this->uid,$cart_item_id);
                $cart_item = $cart_item_mgr->get_entity();
                $this->items[] = $cart_item;
            }
        }
    }


    protected function set_cart_totals($cart_items)
    {
        $totals = $this->get_cart_totals($cart_items);
        if ($this->cart instanceof CartRequestModel) {
            $this->cart->total = $totals['total'];
        }
        if ($this->cart instanceof Cart) {
            $this->cart->total = $totals['total'];
        }
    }

    protected function get_cart_totals($cart_items)
    {
        $totals['total'] = 0;
        if (is_array($cart_items)) {
            for ($i = 0; $i < sizeof($cart_items); $i++) {
                $item = $cart_items[$i];
                if ($item instanceof CartItem) {
                    $totals['total'] += $item->total;
                }
                if ($item instanceof CartItemRequestModel) {
                    $totals['total'] += $item->total;
                }
                if ($item instanceof CartItemResponseModel) {
                    $totals['total'] += $item->total;
                }
            }
        }
        return $totals;
    }

    public function get_cart()
    {
        if ($this->cart instanceof Cart) {
            $cart_response = new CartResponseModel();
            $cart_response->cart_id = $this->cart->id;
            $cart_response->customer_id = $this->cart->customer_id;
            $cart_response->user_id = $this->cart->user_id;
            $cart_response->item_count = $this->cart->item_count;
            $cart_response->total = $this->cart->total;
            $cart_response->currency = $this->cart->currency;
            $cart_response->storefront_key = $this->cart->storefront_key;

            for ($i = 0; $i < sizeof($this->items); $i++) {
                $item_mgr = new CartItemManager($this->uid,$this->items[$i]);
                $cart_response->cart_items[] = $item_mgr->get_cart_item();
            }
        }
        return $cart_response;
    }

    public function get_cart_request()
    {
        $cart_request = "";
        if ($this->cart instanceof Cart) {
            $cart_request = new CartRequestModel();
            $cart_request->cart_id = $this->cart->id;
            $cart_request->customer_id = $this->cart->customer_id;
            $cart_request->user_id = $this->cart->user_id;
            $cart_request->item_count = $this->cart->item_count;
            $cart_request->total = $this->cart->total;
            $cart_request->currency = $this->cart->currency;
            $cart_request->storefront_key = $this->cart->storefront_key;

            for ($i = 0; $i < sizeof($this->items); $i++) {
                // ($this->uid == 0)? $key = 'V'.($i + 1) : $key = $i;
                // $cart_item = $this->items[$key];
                $cart_item = $this->items[$i];
                $item_mgr = new CartItemManager($this->uid,$cart_item);
                $cart_request->cart_items[] = $item_mgr->get_cart_item_request();
            }
        }
        return $cart_request;
    }

    protected function merge_cart()
    {
        if ($this->cart instanceof CartRequestModel) {
            $virtual_items = $this->cart->cart_items;
        }

        $saved_cart_items_request = array();
        for ($i = 0; $i < sizeof($this->items); $i++) {
            $item_mgr = new CartItemManager($this->uid,$this->items[$i]);
            $saved_cart_items_request[] = $item_mgr->get_cart_item_request();
        }

        $process_items = array();

        if($virtual_items){
            for ($i = 0; $i < sizeof($virtual_items); $i++) {
                $stand_ref_id = $virtual_items[$i]->stand_ref_id;
                $check = array();
                $cart_item_request = new CartItemRequestModel;
                for ($j = 0; $j < sizeof($saved_cart_items_request); $j++) {
                    if ($stand_ref_id == $saved_cart_items_request[$j]->stand_ref_id){

                        $check[] = 'exist';
                        $vitems = (array)$virtual_items[$i];
                        $sitems = (array)$saved_cart_items_request[$j];

                        $merge_item = array_merge($vitems,$sitems);
                        foreach ($merge_item as $key => $value) {
                            $cart_item_request->$key = $value;
                        }
                        break;
                    }else{
                        $check[] = 'noexist';
                    }
                }
                if(in_array('exist',$check)){
                    $process_items[] = $cart_item_request;
                }else{
                    $process_items[] = $virtual_items[$i];
                }
            }
        }

        if($process_items){
            if ($this->cart instanceof CartRequestModel) {
                $save = true;
                $this->cart->cart_items = $process_items;
                $this->save_cart($save);
            }
        }

    }



    public function remove_cart_items($cart_items = null,$save = false)
    {
        if (is_array($cart_items) && $this->cart instanceof Cart) {
            if (!$save)  $this->clear_virtual_cart_items();
            for ($i = 0; $i < sizeof($cart_items); $i++) {
                if ($cart_items[$i] instanceof CartItemRequestModel) {
                    if ($save) {
                        $cart_item_mgr = new CartItemManager($this->uid,$cart_items[$i]);
                        $result = $cart_item_mgr->remove_cart_item($this->cart->id,$save);

                        if ($result > 0) {
                            $cart_item = $cart_item_mgr->get_entity();

                            if ($cart_item instanceof CartItem) {
                                $this->items[] = $cart_item;
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        $this->save_cart_item_virtual($cart_items[$i]);
                    }
                } else {
                    return false;
                }
            }

            $this->set_cart_totals($this->items);
            $this->cart->item_count = sizeof($this->items);
            if ($save) $this->cart->save();
            return true;
        }
        return false;
    }

    public function delete()
    {
        $this->cart->delete();
    }

    public function remove()
    {
        $this->cart->remove();
    }

    public function revoke()
    {
        $this->cart->revoke();
    }

}