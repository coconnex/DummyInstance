<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers;

class APIManager
{
    protected $api_action;
    protected $endpoints;
    protected $request_data;
    const EP_INI_FILE = "Config/endpoints_settings.ini";
    const REQ_INI_FILE = "Config/request_settings.ini";

    public function __construct()
    {
        // $this->obj_error = new ErrorLogger();
    }

    protected function get_endpoints(){
        $action = strtoupper($this->api_action);

        $ep_ini_file_path = dirname(dirname(dirname(__FILE__))). "/" . self::EP_INI_FILE;

        if(file_exists($ep_ini_file_path)) $endpointsSettings = parse_ini_file($ep_ini_file_path,true);

        if(is_array($endpointsSettings) && $endpointsSettings != NULL){
            $this->endpoints = $endpointsSettings[$action.'_SCHEMA'];
        }
    }


    protected function validate_request($req_data){
        $validate_req_status = true;
        $this->request_data = $req_data;
        $action = strtoupper($this->api_action);

        $req_ini_file_path = dirname(dirname(dirname(__FILE__))). "/" . self::REQ_INI_FILE;
        if(file_exists($req_ini_file_path)) $requestSettings = parse_ini_file($req_ini_file_path,true);

        if(is_array($requestSettings) && $requestSettings != NULL){
            $re_schema = $requestSettings[$action.'_REQUEST_SCHEMA'];
        }

        //Strict check for unecessary parameters
        foreach($this->request_data as $key => $value){
            if(!key_exists($key,$re_schema)){
                $msg = "Unable to process request. Unexpected parameter " . $key . ". Please remove to process request";
                $validate_req_status = false;
                $validate_req_response = json_encode(array('status' => $validate_req_status, 'msg' => $msg));
                return $validate_req_response;
            }
        }

        //Data validations
        foreach($re_schema as $json_param => $valkey){
            list($type,$required) = explode("_",$valkey);
            $type = strtoupper(trim($type));
            $required =  strtoupper(trim($required));

            if($required === 'MANDATORY' && !key_exists($json_param,(array)$this->request_data)){
                $msg = "Unable to process request. Expected parameter " . $json_param . " not present";
                $validate_req_status = false;
                $validate_req_response = json_encode(array('status' => $validate_req_status, 'msg' => $msg));
                return $validate_req_response;
            }
            if($required === 'MANDATORY' && !(strlen($this->request_data->{$json_param}) > 0)){
                $msg = "Unable to process request. Expected parameter " . $json_param . " is empty";
                $validate_req_status = false;
                $validate_req_response = json_encode(array('status' => $validate_req_status, 'msg' => $msg));
                return $validate_req_response;
            }
            if($required === 'MANDATORY' || ($required === 'OPTIONAL' && !empty($this->request_data->{$json_param}))){
                switch($type){
                    case 'B64NUM':
                        if(!is_numeric(base64_decode($this->request_data->{$json_param}))){
                            $msg = "Unable to process request. Expected parameter " . $json_param . " is not a BASE64 encoded number";
                            $validate_req_status = false;
                            $validate_req_response = json_encode(array('status' => $validate_req_status, 'msg' => $msg));
                            return $validate_req_response;
                        }
                        break;
                }
            }

        }

        $validate_req_response = json_encode(array('status' => $validate_req_status, 'msg' => 'Success'));
        return $validate_req_response;
    }

}