<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Masters;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class SectorsGetData extends APIConnector{

    protected $cat_id;
    protected $cat_data;

    public function __construct($cat_id=0){
        parent::__construct('masters');
        if(is_numeric($cat_id ) && $cat_id > 0){
            $this->cat_id = $cat_id;
        }

    }

    public function get_response(){
        $response_status = 1;
        $response_message = "";
        // echo "hi"; exit;
        if($this->cnx_api_key != ""){
            $method = 'GET';
            $action_endpoint_url = "get/".base64_encode($this->cat_id);
            // debug($action_endpoint_url,1);
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