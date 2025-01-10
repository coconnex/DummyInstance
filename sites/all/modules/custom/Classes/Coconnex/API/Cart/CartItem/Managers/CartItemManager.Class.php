<?php

namespace Coconnex\API\Cart\CartItem\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/CartItem.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/CartItemRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/ResponseModels/CartItemResponseModel.Class.php");

use Coconnex\API\Cart\CartItem\Models\EntityModels\CartItem;
use Coconnex\API\Cart\CartItem\Models\RequestModels\CartItemRequestModel;
use Coconnex\API\Cart\CartItem\Models\ResponseModels\CartItemResponseModel;

Class CartItemManager{

    public $cart_item;
    public $uid;

    public function __construct($uid,$cart_item)
    {
        $this->uid = $uid;
        if ($cart_item instanceof CartItemRequestModel || $cart_item instanceof CartItem) $this->cart_item = $cart_item;
        if (is_numeric($cart_item)) $this->cart_item = new CartItem($this->uid, $cart_item);
    }

    public function get_entity()
    {
        return $this->cart_item;
    }

    public function save_cart_item($cart_id = null,$save = false)
    {
        if ($this->cart_item instanceof CartItemRequestModel) {

            if (!is_numeric($cart_id) && $save) return false;

            $cart_item_entity = new CartItem($this->uid, $this->cart_item->cart_item_id);
            if (isset($this->cart_item->cart_item_id))      $cart_item_entity->id  = $this->cart_item->cart_item_id;
                                                            $cart_item_entity->cart_id = $cart_id;
            if (isset($this->cart_item->stand_ref_id))      $cart_item_entity->inventory_id  = $this->cart_item->stand_ref_id;
            if (isset($this->cart_item->product_key))       $cart_item_entity->product_key  = $this->cart_item->product_key;
            if (isset($this->cart_item->product_name))      $cart_item_entity->product_name  = $this->cart_item->product_name;
            if (isset($this->cart_item->description))       $cart_item_entity->description  = $this->cart_item->description;
            if (isset($this->cart_item->additional_info))   $cart_item_entity->additional_info  = json_encode($this->cart_item->additional_info);
            if (isset($this->cart_item->quantity))          $cart_item_entity->quantity  = $this->cart_item->quantity;
            if (isset($this->cart_item->rate))              $cart_item_entity->rate  = round($this->cart_item->rate, 2);
                                                            $cart_item_entity->total  = round($cart_item_entity->quantity * $cart_item_entity->rate, 2);
            if (isset($this->cart_item->status))            $cart_item_entity->status  = $this->cart_item->status;
            if (isset($this->cart_item->item_group_key))    $cart_item_entity->item_group_key  = $this->cart_item->item_group_key;

            if ($save) {
                $result = $cart_item_entity->save();
                $cart_item_id = null;
                if ($result > 0) {
                    if (is_numeric($result)) {
                        $cart_item_id = $result;
                    } elseif (isset($this->cart_item->cart_item_id)) {
                        $cart_item_id = $this->cart_item->cart_item_id;
                    }
                    $this->cart_item = new CartItem($this->uid, $cart_item_id);
                }
                return $result;
            } else {
                $this->cart_item = $cart_item_entity;
            }
        }
    }

    function get_cart_item()
    {
        if ($this->cart_item instanceof CartItem) {
            $cart_item_response = new CartItemResponseModel();
            $cart_item_response->cart_item_id = $this->cart_item->id;
            $cart_item_response->cart_id = $this->cart_item->cart_id;
            $cart_item_response->stand_ref_id = $this->cart_item->inventory_id;
            $cart_item_response->product_key = $this->cart_item->product_key;
            $cart_item_response->product_name = $this->cart_item->product_name;
            $cart_item_response->description = $this->cart_item->description;
            $cart_item_response->additional_info = json_decode($this->cart_item->additional_info);
            $cart_item_response->quantity = $this->cart_item->quantity;
            $cart_item_response->rate = $this->cart_item->rate;
            $cart_item_response->total = $this->cart_item->total;
            $cart_item_response->status = $this->cart_item->status;
            $cart_item_response->item_group_key = $this->cart_item->item_group_key;
            return $cart_item_response;
        }
    }

    function get_cart_item_request()
    {
        if ($this->cart_item instanceof CartItem) {
            $cart_item_request = new CartItemRequestModel;
            $cart_item_request->cart_item_id = $this->cart_item->id;
            $cart_item_request->cart_id = $this->cart_item->cart_id;
            $cart_item_request->stand_ref_id = $this->cart_item->inventory_id;
            $cart_item_request->product_key = $this->cart_item->product_key;
            $cart_item_request->product_name = $this->cart_item->product_name;
            $cart_item_request->description = $this->cart_item->description;
            $cart_item_request->additional_info = json_decode($this->cart_item->additional_info);
            $cart_item_request->quantity = $this->cart_item->quantity;
            $cart_item_request->rate = $this->cart_item->rate;
            $cart_item_request->total = $this->cart_item->total;
            $cart_item_request->status = $this->cart_item->status;
            $cart_item_request->item_group_key = $this->cart_item->item_group_key;
            return $cart_item_request;
        }
    }

    public function remove_cart_item($save = false)
    {
        // if ($this->cart_item instanceof CartItemRequestModel) {

            if (!is_numeric($this->cart_item->id) && $save) return false;

            $cart_item_entity = new CartItem($this->uid, $this->cart_item->id);
            $cart_item_entity->status  = 'CANCELLED';

            if ($save) {
                $result = $cart_item_entity->save();
                $cart_item_id = null;
                if ($result > 0) {
                    if (is_numeric($result)) {
                        $cart_item_id = $result;
                    } elseif (isset($this->cart_item->id)) {
                        $cart_item_id = $this->cart_item->id;
                    }
                    $this->cart_item = new CartItem($this->uid, $cart_item_id);
                }
                return $result;
            } else {
                $this->cart_item = $cart_item_entity;
            }
        // }
    }

    public function delete()
    {
        $this->cart_item->delete();
    }

    public function remove()
    {
        $this->cart_item->remove();
    }

    public function revoke()
    {
        $this->cart_item->revoke();
    }

}