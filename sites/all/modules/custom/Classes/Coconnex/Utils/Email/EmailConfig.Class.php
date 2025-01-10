<?php

require_once(drupal_get_path('module', 'smtp') . '/phpmailer/class.phpmailer.php');

class EmailConfig {
    public $mailer;


    public function __construct() {

        $this->mailer = new PHPMailer();
        $this->configureSMTP();
    }

    public function configureSMTP() {
        $this->mailer->IsHTML(true);
        $this->mailer->IsSMTP();
        $this->mailer->SMTPAuth = true;

        $this->mailer->Host = variable_get('smtp_host', '');
        $this->mailer->Username = variable_get('smtp_username', '');
        $this->mailer->Password = variable_get('smtp_password', '');
        $this->mailer->Port = variable_get('smtp_port', '25');
        $this->mailer->SMTPSecure = variable_get('smtp_protocol', 'standard');

        $this->mailer->From = variable_get('smtp_from', '');
        $this->mailer->FromName = variable_get('smtp_fromname', '');
    }

}

?>

