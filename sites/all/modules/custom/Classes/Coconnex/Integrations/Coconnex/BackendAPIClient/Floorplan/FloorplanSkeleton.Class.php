<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class FloorplanSkeleton extends APIConnector{

    protected $hall_id;

    public function __construct($hall_id){
        parent::__construct('floorplan');
        if($hall_id != ""){
            $this->hall_id = $hall_id;
        }
    }

    public function get_response(){

        $response_status = 1;
        $response_message = "";

        if($this->cnx_api_key != ""){
            $method = 'GET';
            $action_endpoint_url = base64_encode($this->hall_id).'/svg/base';

            $response = $this->send($method,$action_endpoint_url,"","","RAW");
            // debug($response,1);
            if(trim(json_decode($response)) === ""){
                return $response;
            }else{
                $response = json_decode($response, true);
                if(isset($response['response_status'])){
                    $response_status = 0;
                    $response_message = (isset($response['message'])) ? $response['message'] : "";
                }
            }
        }else{
            $response_status = 0;
            $response_message = 'Sorry! CNX API KEY not found.';
        }
        return array("status" => $response_status, "message" => $response_message);
    }

}