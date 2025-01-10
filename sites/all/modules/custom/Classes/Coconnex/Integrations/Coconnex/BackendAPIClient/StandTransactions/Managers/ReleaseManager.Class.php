<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers;

require_once(dirname(__FILE__) . "/APIManager.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Release.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Lookup.Class.php");
require_once(dirname(__FILE__) . "/TokenManager.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/ReleaseRequestModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\APIManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Lookup;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Release;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\TokenManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\ReleaseRequestModel;

class ReleaseManager extends APIManager
{
    protected $stand_status;
    protected $stand_number;
    protected $stand_nid;
    protected $token;
    protected $exhib_mail;
    public $release_response;

    public function __construct($stand_obj,$token,$exhib_mail)
    {
        parent::__construct();
        $this->token = $token;
        $this->stand_status = $stand_obj->stand_status;
        $this->stand_number = $stand_obj->stand_number;
        $this->stand_nid = $stand_obj->stand_nid;
        $this->exhib_mail = $exhib_mail;
        $this->api_action = "release";
        $this->get_endpoints();
        $this->process();
    }


    protected function process(){
        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'token' && $this->token == ""){

                    $stand_key = $_SESSION['api_call_data'][$this->stand_nid]['stand_key'];
		            $exhib_key = $_SESSION['api_call_data'][$this->stand_nid]['exhibitor_key'];
                    if($stand_key == "" || $exhib_key == ""){

                        $lookup = new Lookup($this->stand_number);

                        $lookup_response = $lookup->get_response();
                        $lookup_response = json_decode($lookup_response);

                        if($lookup_response->status){
                            $stand_key = $lookup_response->data->stand_key;
                            $exhib_key = $lookup_response->data->exhibitor_key;
                            if($stand_key != "" && $exhib_key != ""){
                                $token = new TokenManager($stand_key,$exhib_key,$this->exhib_mail);

                                $token_response = $token->token_response;
                                $token_response = json_decode($token_response);

                                if($token_response->status){
                                    $this->token = $token_response->token;
                                }else{
                                    $this->release_response = json_encode(array('status' => $token_response->status, 'msg' => $token_response->msg));
                                }
                            }else{
                                $this->release_response = json_encode(array('status' => false, 'msg' => 'Unable to process. Insufficient parameters.'));
                            }
                        }else{
                            $this->release_response = json_encode(array('status' => $lookup_response->status, 'msg' => $lookup_response->msg));
                        }
                    }

                }

                if($endpoint == 'release' && $this->token != ""){

                    $request_data = $this->set_release_req_model();

                    $validate_req_response = $this->validate_request($request_data);
                    $validate_req_response = json_decode($validate_req_response);

                    if($validate_req_response->status){
                        $release = new Release($this->token,$this->exhib_mail);
                        $response = $release->get_response($endpoint,$request_data);
                        $response = json_decode($response);

                        if($response->status){
                            $this->release_response = json_encode(array('status' => $response->status, 'msg' => 'Success','data' => $response->data));
                        }else{
                            $this->release_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        }
                    }else{
                        $this->release_response = json_encode(array('status' => $validate_req_response->status, 'msg' => $validate_req_response->msg));
                    }
                }
            }
        }
    }



    protected function set_release_req_model(){
        $book_req_model = new ReleaseRequestModel();
        if($book_req_model){
            $book_req_model->token = $this->token;
            $book_req_model->user_reference = $this->exhib_mail;
        }
        return $book_req_model;
    }
}