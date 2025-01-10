<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Registration;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class ExhibitorGetData extends APIConnector{

    protected $exhib_id;
    protected $exhib_data;

    public function __construct($exhib_id=0){
        parent::__construct('registration');
        if($exhib_id > 0){
            $this->exhib_id = $exhib_id;
        }
       
    }

    public function get_response(){
        $response_status = 1;
        $response_message = "";

        if($this->cnx_api_key != ""){
            $method = 'GET';
            $action_endpoint_url = "get/".base64_encode($this->exhib_id);
            // debug($action_endpoint_url,1);
            $response = $this->send($method,$action_endpoint_url);
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