<?
namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Connector;

use Coconnex\Integrations\Coconnex\APIClients\REST\RestClient;

require_once dirname(dirname(dirname(__FILE__)))."/APIClients/REST/RestClient.Class.php";

class APIConnector{
    protected $endpoint_url;
    protected $endpoint_transaction_url;
    protected $cnx_api_key;
    const CON_INI_FILE = "Config/connection_settings.ini";
    protected $type;
    protected $api_action;
    protected $endpoints;
    const EP_INI_FILE = "Config/endpoints_settings.ini";

    public function __construct($type)
    {
        $this->type = strtoupper($type);
        $this->initialise_connection_parameters();
    }

    protected function initialise_connection_parameters(){
        $con_ini_file_path = dirname(dirname(__FILE__)). "/" . self::CON_INI_FILE;

        if(file_exists($con_ini_file_path)) $connectionSettings = parse_ini_file($con_ini_file_path,true);

        if(is_array($connectionSettings) && $connectionSettings != NULL){
            $this->endpoint_url = $connectionSettings['CONNECTION_SETTINGS_SCHEMA']['END_POINT_BASE_PART'];
            // $this->endpoint_transaction_url = $connectionSettings['CONNECTION_SETTINGS_SCHEMA']['END_POINT_TRANSACTION_PART'];
            $this->endpoint_transaction_url = $connectionSettings[$this->type.'_END_POINT_SCHEMA']['END_POINT_PART'];
            $this->cnx_api_key = $connectionSettings['CONNECTION_SETTINGS_SCHEMA']['CNX_API_KEY'];
        }
    }

    protected function send($method,$action_endpoint_url,$data = "",$data_type = "", $response_type = "JSON"){

        $url = $this->endpoint_url.$this->endpoint_transaction_url.$action_endpoint_url;
        // debug($url,1);
        $r = new RestClient($method, $url, $data, $data_type, $response_type);
        $r->addOption("x-cnx-api-key", $this->cnx_api_key);
        $responseData = $r->call();

        return $responseData;
    }

    protected function get_endpoints(){
        $action = strtoupper($this->api_action);

        $ep_ini_file_path = dirname(dirname(__FILE__)). "/" . self::EP_INI_FILE;

        if(file_exists($ep_ini_file_path)) $endpointsSettings = parse_ini_file($ep_ini_file_path,true);

        if(is_array($endpointsSettings) && $endpointsSettings != NULL){
            $this->endpoints = $endpointsSettings[$action.'_SCHEMA'];
        }
    }


}