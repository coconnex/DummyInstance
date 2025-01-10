<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions;

require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnector.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/StandTransactions/Models/ResponseModels/LookupResponseModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\ResponseModels\LookupResponseModel;

class Lookup extends APIConnector{

    protected $stand_number;

    public function __construct($stand_number)
    {
        parent::__construct('transactions');
        if($stand_number != ""){
            $this->stand_number = $stand_number;
            $this->api_action = "lookup";
            $this->get_endpoints();
        }
    }

    public function get_response(){
        $lookup_response_status = false;

        if($this->cnx_api_key != ""){
            $method = 'GET';
            $action_endpoint_url = $this->endpoints['ENDPOINT'][0].'/'.urlencode($this->stand_number);

            $response = $this->send($method,$action_endpoint_url);

            if($response['response_status']){
                $lookup_response_status = true;
                $lookup_response_data = $this->set_response_model($response['response_data']);

                $lookup_response = json_encode(array('status' => $lookup_response_status, 'data' => $lookup_response_data));

            }else{
                $error_message = $response['message'];
                $lookup_response = json_encode(array('status' => $lookup_response_status, 'msg' => $error_message));
            }
        }else{
            $error_message = 'Sorry! CNX API KEY not found.';
            $lookup_response = json_encode(array('status' => $lookup_response_status, 'msg' => $error_message));
        }

        return $lookup_response;
    }

    protected function set_response_model($response){
        $lookup_res_model = new LookupResponseModel();
        if($lookup_res_model){
            $lookup_res_model->stand_key = $response['stand_key'];
            $lookup_res_model->stand_number = $response['stand_number'];
            $lookup_res_model->stand_name = $response['stand_name'];
            $lookup_res_model->stand_hall_key = $response['stand_hall_key'];
            $lookup_res_model->stand_hall_name = $response['stand_hall_name'];
            $lookup_res_model->stand_type_key = $response['stand_type_key'];
            $lookup_res_model->stand_type_name = $response['stand_type_name'];
            $lookup_res_model->stand_sales_status_key = $response['stand_sales_status_key'];
            $lookup_res_model->stand_sales_status_name = $response['stand_sales_status_name'];
            $lookup_res_model->stand_ops_status_key = $response['stand_ops_status_key'];
            $lookup_res_model->stand_ops_status_name = $response['stand_ops_status_name'];
            $lookup_res_model->stand_prm_status_key = $response['stand_prm_status_key'];
            $lookup_res_model->stand_prm_status_name = $response['stand_prm_status_name'];
            $lookup_res_model->stand_zone_key = $response['stand_zone_key'];
            $lookup_res_model->stand_zone_name = $response['stand_zone_name'];
            $lookup_res_model->stand_dimensions_csv = $response['stand_dimensions_csv'];
            $lookup_res_model->stand_height_restriction = $response['stand_height_restriction'];
            $lookup_res_model->stand_open_sides = $response['stand_open_sides'];
            $lookup_res_model->stand_gross_area = $response['stand_gross_area'];
            $lookup_res_model->stand_column_area = $response['stand_column_area'];
            $lookup_res_model->stand_area_adjustment = $response['stand_area_adjustment'];
            $lookup_res_model->stand_net_area = $response['stand_net_area'];
            $lookup_res_model->stand_is_locked = $response['stand_is_locked'];
            $lookup_res_model->exhibitor_key = $response['exhibitor_key'];
            $lookup_res_model->exhibitor_company_name = $response['exhibitor_company_name'];
            $lookup_res_model->exhibitor_product_key = $response['exhibitor_product_key'];
            $lookup_res_model->exhibitor_product_name = $response['exhibitor_product_name'];
            $lookup_res_model->discrepancy_id = $response['discrepancy_id'];
            $lookup_res_model->cnx_event_edition = $response['cnx_event_edition'];
        }
        return $lookup_res_model;
    }

}