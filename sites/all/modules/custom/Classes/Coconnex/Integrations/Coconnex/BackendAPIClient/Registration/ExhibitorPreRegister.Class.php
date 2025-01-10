<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Registration;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use stdClass;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class ExhibitorPreRegister extends APIConnector{

    public $exhib_id;
    public $sf_data;

    public function __construct($exhib_id = '',$sf_data=''){
        parent::__construct('registration');
        if($exhib_id != ''){
            $this->exhib_id = $exhib_id;
            $this->sf_data = $sf_data;
        }

    }

    public function set_response(){
        $response_status =1;
        $response_message = "";
        if($this->cnx_api_key != ""){
            $method = 'POST';
            $data = json_encode($this->sf_data);
            // debug( $data,1);
            $action_endpoint_url = "autoreg/".base64_encode($this->exhib_id);
            $response = $this->send($method,$action_endpoint_url,$data,"JSON","RAW");
            // debug($response,1);
            if(isset($response['response_status'])){
                $response_status = 0;
                $response_message = (isset($response['message'])) ? $response['message'] : "";
            }else{
                return json_decode($response);
            }
        }else{
            $response_status = 0;
            $response_message = 'Sorry! CNX API KEY not found.';
        }
        return array("status" => $response_status, "message" => $response_message);
    }
}