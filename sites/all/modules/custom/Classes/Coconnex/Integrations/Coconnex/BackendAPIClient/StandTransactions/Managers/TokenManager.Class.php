<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers;

require_once(dirname(__FILE__) . "/APIManager.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Token.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/TokenRequestModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\APIManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Token;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\TokenRequestModel;

class TokenManager extends APIManager
{
    protected $stand_key;
    protected $exhib_key;
    protected $exhib_mail;
    public $token_response;

    public function __construct($stand_key,$exhib_key,$exhib_mail)
    {
        parent::__construct();
        if($stand_key != "" && $exhib_key != ""){
            $this->stand_key = $stand_key;
            $this->exhib_key = $exhib_key;
            $this->exhib_mail = $exhib_mail;
            $this->api_action = "token";
            $this->get_endpoints();
            $this->process();
        }
    }

    protected function process(){
        if(is_array($this->endpoints)){
            for($i = 0; $i < count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];
                $request_data = $this->set_token_req_model();

                $validate_req_response = $this->validate_request($request_data);
                $validate_req_response = json_decode($validate_req_response);

                if($validate_req_response->status){
                    $token = new Token($this->stand_key,$this->exhib_key,$this->exhib_mail);
                    $response = $token->get_response($endpoint,$request_data);
                    $response = json_decode($response);

                    if($response->status){
                        $res_data = $response->data;

                        $token = $res_data->token;
                        $this->token_response = json_encode(array('status' => $response->status, 'token' => $token));
                    }else{
                        $this->token_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                    }
                }else{
                    $this->token_response = json_encode(array('status' => $validate_req_response->status, 'msg' => $validate_req_response->msg));

                }
            }
        }
    }

    protected function set_token_req_model(){
        $token_req_model = new TokenRequestModel();
        if($token_req_model){
            $token_req_model->cnx_stand_key = $this->stand_key;
            $token_req_model->cnx_exhibitor_key = $this->exhib_key;
            $token_req_model->user_reference = $this->exhib_mail;
        }
        return $token_req_model;
    }

}