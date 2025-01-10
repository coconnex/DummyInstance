<?php
namespace Coconnex\Integrations\Coconnex\APIClients\REST;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RESTClient: Calls various REST servers for fetching data.
 *
 * @author abhijeetg
 */
class RestClient {
	protected $method;
	protected $url;
	protected $data;
	protected $type;
	protected $options;
	protected $returntype;


    public function __construct($method="", $url="", $data="", $type="", $returntype="json"){
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
        $this->type = $type;
        $this->options = array();
	    $this->returntype = $returntype;
        $this->initOptions();
    }

    protected function initOptions(){
        if(strtoupper($this->type) == 'JSON'){
            $this->options[]="content-type: application/json; charset=utf-8";
        }
        if(strtoupper($this->type) == 'ENC'){
            $this->options[]="content-type: application/x-www-form-urlencoded";
        }
        if(strtoupper($this->type) == 'TXT'){
            $this->options[]="content-type: text/plain";
        }

        return;
    }

    public function addOption($name, $value){
        $this->options[] = $name.": ".$value;
        return;
    }

    public function setData($data){
        $this->data = $data;
        return;
    }

    public function call(){
        $curl = curl_init();
        switch (strtoupper($this->method)){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              //curl_setopt($curl, CURLOPT_HEADER, 1);
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
              if ($this->data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
              break;

           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($this->data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
              break;
           default:
              if ($this->data)
                 $this->url = sprintf("%s?%s", $this->url, http_build_query($this->data));
        }
        // OPTIONS:
	    $this->addOption('Content-length', strlen($this->data));

        if ($this->url != "") {
            $urlProtocol = parse_url($this->url, PHP_URL_SCHEME);
        }

        if ($urlProtocol == 'https' || $urlProtocol == 'http') {

            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->options);
            // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_ENCODING ,"");
            // curl_setopt($curl, CURLOPT_FAILONERROR, true);
            // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:

            $result = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if($result===false){
                return json_encode(array("statusCode" => $status, "message" => curl_error($curl)));
                // $error_msg = json_encode(array("statusCode" => $status, "message" => curl_error($curl)));
            }
            // echo "<pre>"; echo $status." .... ".$error_msg." ---- ".$result; exit;
            curl_close($curl);
            return $this->returnResponse($result);

        }else {
            $errMsg = "Protocol not correct of URL: " . $this->url;

            // ADD LOG HERE
            return json_encode(array("statusCode" => '0', "message" => $errMsg));
        }
    }

    protected function returnResponse($resultdata){
        if(strtoupper($this->returntype) == 'JSON'){
            return json_decode($resultdata, true);
        }else{
            return $resultdata;
        }
    }
}
