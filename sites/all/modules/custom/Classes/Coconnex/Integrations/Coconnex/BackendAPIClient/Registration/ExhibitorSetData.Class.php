<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Registration;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class ExhibitorSetData extends APIConnector{

    protected $exhib_id;
    protected $exhib_data;

    public function __construct($exhib_id=0, $exhib_data=0){
        parent::__construct('registration');
        
        if($exhib_id == 0 && $exhib_data != ''){
            // debug($exhib_data,1);
            $this->exhib_data = $exhib_data;
        }
       
    }

    public function set_response(){
        
        $response_status =1;
        $response_message = "";
        
        if($this->cnx_api_key != ""){
            $method = 'POST';
            $data = $this->exhib_data;
            $action_endpoint = "save";
            $response = $this->send($method,$action_endpoint,$data,"JSON","RAW");
            // debug( $response,1);
            if(isset($response['response_status'])){
                $response_status = 0;
                $response_message = (isset($response['message'])) ? $response['message'] : "";
            }else{
                return  json_decode($response);
            }
        }else{
            $response_status = 0;
            $response_message = 'Sorry! CNX API KEY not found.';
        }
        return array("status" => $response_status, "message" => $response_message);
    }
}