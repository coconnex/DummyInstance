<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions;

// require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnecter.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/StandTransactions/Models/ResponseModels/ReleaseResponseModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\ReleaseRequestModel;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\ResponseModels\ReleaseResponseModel;

class Release extends APIConnector{

    public function __construct()
    {
        parent::__construct('transactions');
    }


    public function get_response($endpoint,$req_data){
        $release_response_status = false;

        if($req_data instanceof ReleaseRequestModel){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                $response = $this->send($method,$endpoint,$data);

                if($response['response_status']){
                    $release_response_status = true;
                    $release_response_data = $this->set_response_model($response['message']);

                    $release_response = json_encode(array('status' => $release_response_status, 'data' => $release_response_data));
                }else{
                    $error_message = $response['message'];
                    $release_response = json_encode(array('status' => $release_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $release_response = json_encode(array('status' => $release_response_status, 'msg' => $error_message));
            }
        }
        return $release_response;
    }

    protected function set_response_model($message){
        $release_res_model = new ReleaseResponseModel();
        if($release_res_model){
            $release_res_model->message = $message;
        }
        return $release_res_model;
    }

}