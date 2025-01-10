<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Order;

// require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnecter.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Order/Models/ResponseModels/OrderResponseModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Models\RequestModels\OrderRequestModel;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Models\ResponseModels\OrderResponseModel;

class Order extends APIConnector{

    public function __construct()
    {
        parent::__construct('order');
    }

    public function get_response($endpoint,$req_data){
        $order_response_status = false;

        if($req_data instanceof OrderRequestModel){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);
                // debug($response,1);
                if($response['order_id'] > 0){
                    $order_response_status = true;
                    $order_response_data = $this->set_response_model($response);

                    $order_response = json_encode(array('status' => $order_response_status, 'data' => $order_response_data));
                }else{
                    $error_message = 'Issue while creating a order. Please try again later.';
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    public function get_pdf_response($endpoint,$req_data){
        $order_response_status = false;

        if(is_array($req_data)){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);
                // debug($response,1);
                if($response['pdf_path']){
                    $response_pdf = json_decode($response['pdf_path']);
                    $order_response_status = true;
                    // $basepath = str_replace('rest/1.5.10/','',$this->endpoint_url);
                    // $order_response = json_encode(array('status' => $order_response_status, 'data' => array('pdf_path' => $basepath.$response['pdf_path'])));
                    $order_response = json_encode(array('status' => $order_response_status, 'data' => array('file_name' => $response_pdf->name,'pdf_path' => $response_pdf->data)));
                }else{-
                    $error_message = $response['message'];
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    public function get_link_response($endpoint,$req_data){
        $order_response_status = false;

        if(is_array($req_data)){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);
                // debug($response,1);
                if($response['order_link']){
                    $order_response_status = true;
                    $order_response = json_encode(array('status' => $order_response_status, 'data' => array('order_link' => $response['order_link'])));
                }else{
                    $error_message = $response['message'];
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    public function get_orders_response($endpoint,$req_data){
        $order_response_status = false;

        if(is_array($req_data)){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);
                // debug($response,1);
                if(is_array($response['orders']) && sizeof($response['orders']) > 0){
                    $order_response_status = true;
                    $order_response = json_encode(array('status' => $order_response_status, 'data' => $response['orders']));
                }else{
                    $error_message = $response['message'];
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    public function get_order_items_response($endpoint,$req_data){
        $order_response_status = false;

        if(is_array($req_data)){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);

                if(is_array($response['order_items']) && sizeof($response['order_items']) > 0){
                    $order_response_status = true;
                    $order_response = json_encode(array('status' => $order_response_status, 'data' => $response['order_items']));
                }else{
                    $error_message = $response['message'];
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    public function get_cancel_requst_response($endpoint,$req_data){
        $order_response_status = false;

        if(is_array($req_data)){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data,1);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);
                // debug($response,1);
                if($response['order_id']){
                    $order_response_status = true;
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => 'Cancellation request submitted successfully.', 'data' => array()));
                }else{
                    $error_message = $response['message'];
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    public function get_cancel_response($endpoint,$req_data){
        $order_response_status = false;

        if(is_array($req_data)){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                // debug($data,1);
                // debug($endpoint,1);
                $response = $this->send($method,$endpoint,$data);
                // debug($response,1);
                if($response['order_id']){
                    $order_response_status = true;
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => 'Cancellation done successfully.', 'data' => array()));
                }else{
                    $error_message = $response['message'];
                    $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $order_response = json_encode(array('status' => $order_response_status, 'msg' => $error_message));
            }
        }
        return $order_response;
    }

    
    protected function set_response_model($response){
        $order_res_model = new OrderResponseModel();
        if($order_res_model){
            $order_res_model->order_id = $response['order_id'];
            $order_res_model->link_id = $response['link_id'];
            $order_res_model->link = $response['link'];
            $order_res_model->message = 'Order Created Successfully';
        }
        return $order_res_model;
    }

}