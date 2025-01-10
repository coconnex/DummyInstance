<?
namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions;

// require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnector.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/StandTransactions/Models/ResponseModels/ReserveResponseModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\ReserveRequestModel;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\ResponseModels\ReserveResponseModel;


class Reserve extends APIConnector{

    public function __construct()
    {
        parent::__construct('transactions');
    }

    public function get_response($endpoint,$req_data){
        $reserve_response_status = false;

        if($req_data instanceof ReserveRequestModel){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                $response = $this->send($method,$endpoint,$data);

                if($response['response_status']){
                    $reserve_response_status = true;
                    $reserve_response_data = $this->set_response_model($response['token'],$response['response_data']);

                    $reserve_response = json_encode(array('status' => $reserve_response_status, 'data' => $reserve_response_data));
                }else{
                    $error_message = $response['message'];
                    $reserve_response = json_encode(array('status' => $reserve_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $reserve_response = json_encode(array('status' => $reserve_response_status, 'msg' => $error_message));
            }
        }
        return $reserve_response;
    }

    protected function set_response_model($token,$response){
        $reserve_res_model = new ReserveResponseModel();
        if($reserve_res_model){
            $reserve_res_model->token = $token;
            $reserve_res_model->stand_key = $response['stand_key'];
            $reserve_res_model->stand_number = $response['stand_number'];
            $reserve_res_model->stand_name = $response['stand_name'];
            $reserve_res_model->stand_hall_key = $response['stand_hall_key'];
            $reserve_res_model->stand_hall_name = $response['stand_hall_name'];
            $reserve_res_model->stand_type_key = $response['stand_type_key'];
            $reserve_res_model->stand_type_name = $response['stand_type_name'];
            $reserve_res_model->stand_sales_status_key = $response['stand_sales_status_key'];
            $reserve_res_model->stand_sales_status_name = $response['stand_sales_status_name'];
            $reserve_res_model->stand_ops_status_key = $response['stand_ops_status_key'];
            $reserve_res_model->stand_ops_status_name = $response['stand_ops_status_name'];
            $reserve_res_model->stand_zone_key = $response['stand_zone_key'];
            $reserve_res_model->stand_zone_name = $response['stand_zone_name'];
            $reserve_res_model->stand_dimensions_csv = $response['stand_dimensions_csv'];
            $reserve_res_model->stand_height_restriction = $response['stand_height_restriction'];
            $reserve_res_model->stand_open_sides = $response['stand_open_sides'];
            $reserve_res_model->stand_gross_area = $response['stand_gross_area'];
            $reserve_res_model->stand_column_area = $response['stand_column_area'];
            $reserve_res_model->stand_area_adjustment = $response['stand_area_adjustment'];
            $reserve_res_model->stand_net_area = $response['stand_net_area'];
            $reserve_res_model->stand_is_locked = $response['stand_is_locked'];
            $reserve_res_model->exhibitor_key = $response['exhibitor_key'];
            $reserve_res_model->exhibitor_company_name = $response['exhibitor_company_name'];
            $reserve_res_model->exhibitor_product_key = $response['exhibitor_product_key'];
            $reserve_res_model->exhibitor_product_name = $response['exhibitor_product_name'];
            $reserve_res_model->cnx_event_edition = $response['cnx_event_edition'];
        }
        return $reserve_res_model;
    }

}