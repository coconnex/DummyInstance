<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions;

// require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnecter.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/StandTransactions/Models/ResponseModels/TokenResponseModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\TokenRequestModel;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\ResponseModels\TokenResponseModel;

class Token extends APIConnector{

    protected $stand_key;
    protected $exhib_key;
    protected $exhib_mail;

    public function __construct($stand_key,$exhib_key,$exhib_mail)
    {
        parent::__construct('transactions');
        if($stand_key != "" && $exhib_key != ""){
            $this->stand_key = $stand_key;
            $this->exhib_key = $exhib_key;
            $this->exhib_mail = $exhib_mail;
            $this->api_action = "token";
            $this->get_endpoints();
        }
    }

    public function get_response($endpoint,$req_data){
        $token_response_status = false;

        if($req_data instanceof TokenRequestModel){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                $response = $this->send($method,$endpoint,$data);

                if($response['response_status']){
                    $token_response_status = true;
                    $token_response_data = $this->set_response_model($response['token']);

                    $token_response = json_encode(array('status' => $token_response_status, 'data' => $token_response_data));
                }else{
                    $error_message = $response['message'];
                    $token_response = json_encode(array('status' => $token_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $token_response = json_encode(array('status' => $token_response_status, 'msg' => $error_message));
            }
        }
        return $token_response;
    }

    protected function set_response_model($token){
        $token_res_model = new TokenResponseModel();
        if($token_res_model){
            $token_res_model->token = $token;
        }
        return $token_res_model;
    }

}