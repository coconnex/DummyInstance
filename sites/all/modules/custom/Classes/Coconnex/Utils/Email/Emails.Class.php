<?php

use Coconnex\Utils\Handlers\TemplateHandler;
use Coconnex\Utils\Config\Config;

require_once dirname(dirname(dirname(__FILE__))). "/Utils/Config/Config.Class.php";
require_once dirname(dirname(dirname(__FILE__)))."/Utils/Email/CNXMail.Class.php";

class Emails extends CNXMail {

    public $templatepath;
    public $filename;
    public $type;
    public $additionalrecipients;

    public function __construct($type = "") {
        parent::__construct();
        $this->templatepath = "";
        $this->filename = "";
        $this->additionalrecipients = "";
        parent::__construct();
        $obj_config = new Config("d6");
        $core_tokens = array(
            'LOGOIMAGE' => $obj_config->baseurl()."/".$obj_config->getLogoPath(),
            'EVENTNAME' => $obj_config::getvar("site_name"),
            'CONTACTUSMAIL' => $obj_config->getvar('ORG_SALES_TEAM_EMAIL'),
            'ORGANISERNAME' => $obj_config::getvar("ORGANISER_NAME")
        );
        $this->addTokens($core_tokens);
        $this->setEmailType($type);
    }

    public function setEmailType($type){
        $this->type = $type;
        $this->initialise();
        $this->setAdditionalRecipients();
        return;
    }

    protected function initialise(){
        if($this->type != ""){
            $this->type = trim(strtoupper($this->type));

            $sql = "SELECT templatepath, filename, subject, additionalrecipients FROM cnx_email_type WHERE typekey = '".$this->type."'";
            $obj_config = new Config("d6");
            $rs = $obj_config::getResultset($sql);
            while($row = $obj_config::getRow($rs)){
                $this->templatepath = realpath('./').$row->templatepath;
                $this->filename = $row->filename;
                $this->subject = $row->subject;
                $this->additionalrecipients = $row->additionalrecipients;
            }

            return;
        }
    }

    protected function setAdditionalRecipients(){
        if($this->additionalrecipients != ''){
            $arr_recipients = json_decode($this->additionalrecipients, true);
            foreach($arr_recipients as $key => $arrvalues){
                $arrvalues= array_filter($arrvalues);
                if($key == "to") {
                    if(count($arrvalues) > 0){
                        $this->setTo($arrvalues);
                    }
                }
                if($key == "cc") {
                    if(count($arrvalues) > 0){
                        $this->setCc($arrvalues);
                    }
                }
                if($key == "bcc") {
                    if(count($arrvalues) > 0){
                        $this->setBcc($arrvalues);
                    }
                }
            }
        }
        return;
    }

    public function applyTokens($tokens){
        $this->addTokens($tokens);
        if($this->type != '' && $this->templatepath != ''){
            $vars = $this->tokens;
            $this->body = TemplateHandler::applyTemplateFile($this->templatepath, $this->filename, $vars);
        }
        return;
    }

}
?>