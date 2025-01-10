<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Registration;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class ExhibitorCheckData extends APIConnector{

    protected $exhib_mail;
    protected $exhib_data;

    public function __construct($exhib_mail=0){
        parent::__construct('registration');
        if($exhib_mail != ''){
            $this->exhib_mail = $exhib_mail;
        }
       
    }

    public function check_response(){
        $response_status = 1;
        $response_message = "";

        if($this->cnx_api_key != ""){
            $method = 'GET';
            $action_endpoint_url = "check/".base64_encode($this->exhib_mail);
            // debug($action_endpoint_url);
            $response = $this->send($method,$action_endpoint_url);
            // debug($response,1);

            if(isset($response['response_status'])){
                $response_status = 0;
                $response_message = (isset($response['message'])) ? $response['message'] : "";
            }else{
                return $response;
            }
        }else{
            $response_status = 0;
            $response_message = 'Sorry! CNX API KEY not found.';
        }
        return array("status" => $response_status, "message" => $response_message);
    }

   
}