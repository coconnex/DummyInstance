<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/ReserveRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Lookup.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Reserve.Class.php");
require_once(dirname(__FILE__) . "/APIManager.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\APIManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Lookup;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Reserve;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\ReserveRequestModel;
use stdClass;

class ReserveManager extends APIManager
{
    protected $stand_number;
    protected $stand_area;
    protected $stand_type;
    protected $stand_nid;
    protected $exhib_name;
    protected $exhib_nid;
    protected $exhib_mail;
    protected $sv_stand_key;
    protected $sv_event_edition;
    public $reserve_response;

    public function __construct($stand_obj,$exhib_obj)
    {
        parent::__construct();
        if($stand_obj instanceof stdClass){
            $this->stand_number = $stand_obj->stand_number;
            $this->stand_area = $stand_obj->stand_area;
            $this->stand_type = $stand_obj->stand_type;
            $this->stand_nid = $stand_obj->stand_nid;

            if($exhib_obj instanceof stdClass){
                $this->exhib_nid = $exhib_obj->exhib_nid;
                $this->exhib_name = $exhib_obj->exhib_name;
                $this->exhib_mail = $exhib_obj->exhib_mail;
            }
            $this->api_action = "reserve";
            $this->get_endpoints();
            $this->process();
        }
    }

    protected function process(){

        if(is_array($this->endpoints)){
            for($i = 0; $i <= count($this->endpoints); $i++){
                $endpoint = $this->endpoints['ENDPOINT'][$i];

                if($endpoint == 'lookup'){
                    $lookup = new Lookup($this->stand_number);

                    $response = $lookup->get_response();
                    $response = json_decode($response);

                    if($response->status){
                        $validate_response = $this->validate_stands($response->data);
                        $validate_response = json_decode($validate_response);

                        if($validate_response->status){
                                $this->sv_stand_key = $response->data->stand_key;
                                $this->sv_event_edition = $response->data->cnx_event_edition;
                        }else{
                            $this->reserve_response = json_encode(array('status' => $validate_response->status, 'msg' => $validate_response->msg));
                            // return $reserve_response;
                        }
                    }else{
                        $this->reserve_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                        // return $reserve_response;
                    }
                }

                if($endpoint == 'reserve' && $this->sv_stand_key != ""){

                    $request_data = $this->set_reserve_req_model();

                    $validate_req_response = $this->validate_request($request_data);
                    $validate_req_response = json_decode($validate_req_response);

                        if($validate_req_response->status){
                            $reserve = new Reserve();
                            $response = $reserve->get_response($endpoint,$request_data);
                            $response = json_decode($response);

                            if($response->status){
                                $res_data = $response->data;
                                $_SESSION['api_call_data'][$this->stand_nid]['stand_key'] = $res_data->stand_key;
                                $_SESSION['api_call_data'][$this->stand_nid]['stand_number'] = $res_data->stand_number;
                                $_SESSION['api_call_data'][$this->stand_nid]['token'] = $res_data->token;
                                $_SESSION['api_call_data'][$this->stand_nid]['exhibitor_key'] = $res_data->exhibitor_key;
                                $this->reserve_response = json_encode(array('status' => $response->status, 'msg' => 'Reserved successfully.','data'=>$res_data));
                            }else{
                                $this->reserve_response = json_encode(array('status' => $response->status, 'msg' => $response->msg));
                            }
                        }else{
                            $this->reserve_response = json_encode(array('status' => $validate_req_response->status, 'msg' => $validate_req_response->msg));
                        }
                }
            }
        }

    }

    public function validate_stands($response_data){
        $validate_status = false;
        if($response_data){
            $sv_stand_status = $response_data->stand_sales_status_key;
            $sv_prime_stand_status = $response_data->stand_prm_status_key;
            if($sv_stand_status == 'AVA' && $sv_prime_stand_status == ""){
                $sv_stand_area = $response_data->stand_gross_area;
                if($this->stand_area == $sv_stand_area){
                    $validate_status = true;
                    $msg = 'Stand available for booking.';
                }else{
                    $msg = 'Sorry! this stand area not match. Please select another stand.';
                }
            }else{
                if($sv_prime_stand_status == 'PRM'){
                    $msg = 'Interested in stand <b>'.$response_data->stand_number.'</b>? Please email us on <a href="mailto:topdrawerexhibitors@clarionevents.com"> topdrawerexhibitors@clarionevents.com</a>';
                }elseif($sv_stand_status == 'RVD'){
                    $msg = 'Sorry! this stand is already reserved by another exhibitor. Please select another stand.';
                }elseif($sv_stand_status == 'OPT'){
                    $msg = 'Sorry! this stand is on hold. Please select another stand.';
                }elseif($sv_stand_status == 'CNT'){
                    $msg = 'Sorry! this stand is already booked by another exhibitor. Please select another stand.';
                }
            }
        }
        $validate_response = json_encode(array('status' => $validate_status, 'msg' => $msg));
        return $validate_response;
    }

    protected function set_reserve_req_model(){
        $reserveReqModel = new ReserveRequestModel();
        if($reserveReqModel){
            $reserveReqModel->cnx_stand_key = $this->sv_stand_key;
            $reserveReqModel->cnx_event_edition = $this->sv_event_edition;
            $reserveReqModel->cnx_ops_status = $this->stand_type;
            $reserveReqModel->stand_name = $this->exhib_name;
            $reserveReqModel->exhibitor = $this->exhib_nid;
            $reserveReqModel->user_reference = $this->exhib_mail;
        }
        return $reserveReqModel;
    }

}