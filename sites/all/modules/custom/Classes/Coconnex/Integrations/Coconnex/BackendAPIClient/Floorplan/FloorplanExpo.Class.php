<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

class FloorplanExpo extends APIConnector{

    protected $hall_id;

    public function __construct($hall_id)
    {
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
            $action_endpoint_url = base64_encode($this->hall_id).'/ifpss';

            // $response = $this->send($method,$action_endpoint_url,"","","RAW");
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

        return array("status" => $response_status, "message" => $response_message);;
    }
}