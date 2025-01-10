<?php

// namespace Coconnex\Utils\Email;
require_once "EmailConfig.Class.php";

class CNXMail extends EmailConfig {

    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $body;
    public $isHtml;
    public $tokens;
    public $result;

    public function __construct() {
        global $base_url;
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->subject = "";
        $this->body = "";
        $this->tokens = array('BASEURL' => $base_url);
        $this->result = true;
        $this->isHtml = true;

        parent::__construct();
    }

    public function setTo($to) {
        if(is_array($to)){
            $this->to = array_merge($this->to, $to);
        }else{
            $this->to[] = $to;
        }
        return;
    }

    public function setCc($cc) {
        if(is_array($cc)){
            $this->cc = array_merge($this->cc, $cc);
        }else{
            $this->cc[] = $cc;
        }
        return;
    }

    public function setBcc($bcc) {
        if(is_array($bcc)){
            $this->bcc = array_merge($this->bcc, $bcc);
        }else{
            $this->bcc[] = $bcc;
        }
        return;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setIsHtml($isHtml) {
        $this->isHtml = $isHtml;
    }

    public function addTokens($tokens){
        $this->tokens = array_merge($this->tokens,$tokens);
    }

    public function attach($path){
        $this->mailer->AddAttachment(realpath('./').$path);
    }

    // public function embedImage($_path = ""){
    //     $path = $_path;
    //     if($_path != "") $path = $this->logopath;
    //     $this->mailer->AddEmbeddedImage(realpath('./').$path,'logoimage','logo.png','base64','image/png');
    //     return;
    // }

    public function send(){
        $this->mailer->IsSMTP();
        foreach($this->to as $emailto){
            $this->mailer->AddAddress($emailto);
        }
        foreach($this->cc as $emailcc){
            $this->mailer->AddCC($emailcc);
        }
        foreach($this->bcc as $emailbcc){
            $this->mailer->AddBCC($emailbcc);
        }
        $this->mailer->Subject = $this->subject;
        $this->mailer->IsHTML($this->isHtml);
        $this->mailer->Body = $this->body;

        $this->result = $this->mailer->Send();

        if($this->result){
            // watchdog("OrderEmail", "SUCCESS: To ".print_r($this->to,1));
        }else{
            // watchdog("OrderEmail", "FAILED: To ".print_r($this->to,1));
        }
        return $this->result;
    }

    public function applytoken(){

    }

}

?>

