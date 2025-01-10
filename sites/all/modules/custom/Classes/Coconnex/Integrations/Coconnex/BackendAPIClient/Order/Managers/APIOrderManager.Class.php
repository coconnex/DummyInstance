<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Managers;
// echo dirname(dirname(dirname(__FILE__))) . "/StandTransactions/Managers/APIManager.Class.php";
require_once(dirname(dirname(dirname(__FILE__))) . "/StandTransactions/Managers/APIManager.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Order.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/OrderRequestModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Models\RequestModels\OrderRequestModel;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\APIManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Order;

class APIOrderManager extends APIManager
{
    protected $order;
    public $order_response;

    public function __construct($order,$action = 'order_create')
    {
        parent::__construct('order');
        $this->order = $order;
        $this->api_action = $action;
        $this->get_endpoints();
        if($this->api_action == 'order_create'){
            $this->process();
        }
    }

    protected function process(){

        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'create'){

                    $request_data = $this->set_order_req_model();

                    $validate_req_response = $this->validate_request($request_data);
                    $validate_req_response = json_decode($validate_req_response);
                    $validate_req_response->status = true;

                    if($validate_req_response->status){
                        $order = new Order();
                        $response = $order->get_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => $validate_req_response->status, 'msg' => $validate_req_response->msg));

                    }
                }
            }
        }
    }

    protected function set_order_req_model(){
        $order_req_model = new OrderRequestModel();
        if($order_req_model){
            $order_req_model->customer_id = $this->order->customer_id;
            $order_req_model->supplier_id = $this->order->supplier_id;
            $order_req_model->order_items = $this->order->order_items;
        }
        return $order_req_model;
    }

    public function get_order_pdf_path(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'getpdf'){

                    $request_data = array('order_id' => $this->order);

                    if(is_numeric($this->order) && $this->order > 0){
                        $order = new Order();
                        $response = $order->get_pdf_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => false, 'msg' => 'Invalid Order Reference'));
                    }
                }
            }
        }
    }

    public function get_order_link(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'getlink'){

                    $request_data = array('order_id' => $this->order);

                    if(is_numeric($this->order) && $this->order > 0){
                        $order = new Order();
                        $response = $order->get_link_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => false, 'msg' => 'Invalid Order Reference'));

                    }
                }
            }
        }
    }

    public function get_orders(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'get'){

                    $request_data = array('customer_id' => $this->order);

                    if(is_numeric($this->order) && $this->order > 0){
                        $order = new Order();
                        $response = $order->get_orders_response($endpoint,$request_data);
                        $response = json_decode($response);
                        // debug($response,1);
                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => false, 'msg' => 'Invalid Order Reference'));

                    }
                }
            }
        }
    }

    public function get_order_items(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'getitems'){

                    $request_data = array('order_id' => $this->order);

                    if(is_numeric($this->order) && $this->order > 0){
                        $order = new Order();
                        $response = $order->get_order_items_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => false, 'msg' => 'Invalid Order Reference'));
                    }
                }
            }
        }
    }

    public function order_cancel_request(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'cancelrequest'){

                    $request_data = array('order_id' => $this->order->order_id, 'reason' => $this->order->reason);

                    if(is_numeric($this->order->order_id) && $this->order->order_id > 0){
                        $order = new Order();
                        $response = $order->get_cancel_requst_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => false, 'msg' => 'Invalid Order Reference'));
                    }
                }
            }
        }
    }

    public function order_cancel(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'cancel'){

                    $request_data = array('order_id' => $this->order->order_id);

                    if(is_numeric($this->order->order_id) && $this->order->order_id > 0){
                        $order = new Order();
                        $response = $order->get_cancel_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->order_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->order_response = json_encode(array('status' => false, 'msg' => 'Invalid Order Reference'));
                    }
                }
            }
        }
    }
}