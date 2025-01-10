<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Packages;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

require_once(dirname(dirname(__FILE__)) . "/Connector/APIConnector.Class.php");

class PackageList extends APIConnector{

    protected $hall_id;
    protected $customer_nid;

    public function __construct($customer_nid){
        $this->customer_nid = $customer_nid;
        parent::__construct('products');
    }

    public function get_response(){

        $response_status = 1;
        $response_message = "";

        if($this->cnx_api_key != ""){
            $method = 'GET';
            $action_endpoint_url = 'get/list/'.$this->customer_nid;

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
        return array("status" => $response_status, "message" => $response_message);
    }

}