<?
namespace Coconnex\API\Reminders\Schedules\Managers;

require_once(dirname(dirname(dirname(__FILE__))) . "/Managers/ReminderManager.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/ReminderSchedule.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) .'/Managers/FormReminderManager.Class.php');
require_once(dirname(dirname(dirname(__FILE__))) .'/Managers/TaskReminderManager.Class.php');
require_once(dirname(dirname(dirname(__FILE__))) .'/Managers/TransactionReminderManager.Class.php');
require_once(dirname(dirname(dirname(__FILE__))) .'/Logs/Managers/ReminderLogManager.Class.php');
require_once(dirname(dirname(dirname(__FILE__))) .'/Logs/Models/RequestModels/ReminderLogRequestModel.Class.php');

use Coconnex\API\Reminders\Email\ReminderEmails;
use Coconnex\API\Reminders\Logs\Managers\ReminderLogManager;
use Coconnex\API\Reminders\Logs\Models\RequestModels\ReminderLogRequestModel;
use Coconnex\API\Reminders\Managers\ReminderManager;
use Coconnex\API\Reminders\Schedules\Models\EntityModels\ReminderSchedule;
use Coconnex\API\Reminders\Managers\FormReminderManager;
use Coconnex\API\Reminders\Managers\TaskReminderManager;
use Coconnex\API\Reminders\Managers\TransactionReminderManager;
use Coconnex\Utils\Config\Config;

class ReminderScheduleManager extends ReminderManager{

    public $uid;
    public $obj_reminder_schedule;

    public function __construct($uid, $schedule)
    {
        $this->uid = $uid;
        if (is_numeric($schedule) && $schedule > 0) $this->obj_reminder_schedule = new ReminderSchedule($uid,$schedule);
        $reminder_id = $this->obj_reminder_schedule->reminder_id;
        parent::__construct($this->uid,$reminder_id);

        if($this->obj_reminder_schedule->is_active == 1){
            $this->process();
        }
    }

    public function process(){
        // require_once(dirname(dirname(dirname(__FILE__))) .'/Managers/'. $this->obj_reminder->class_name.'.Class.php');
        // $class = $this->obj_reminder->class_name;
        // $data = new $class($this->obj_reminder->config);
        // die;
        // debug($this);
        switch (trim(strtoupper($this->obj_reminder->type))) {
            case 'FORM':
                $formReminderObj = new FormReminderManager($this->obj_reminder->config);

                if($formReminderObj instanceof FormReminderManager && is_array($formReminderObj->obj_users) && !empty($formReminderObj->obj_users)){
                    $this->run_schedule($formReminderObj->obj_users);
                }
                break;
            case 'TASK':
                $data = new TaskReminderManager($this->obj_reminder->config);
                break;
            case 'REGISTER':
                break;
            case 'TRANSACTION':

                $reminderObj = new TransactionReminderManager($this->obj_reminder_schedule->frequency_config,$this->obj_reminder_schedule->id);
                // debug($reminderObj,1);
                if($reminderObj instanceof TransactionReminderManager && is_array($reminderObj->obj_users) && !empty($reminderObj->obj_users)){
                    $this->run_schedule($reminderObj->obj_users);
                }
                break;
            default:
                break;
        }

    }

    public function run_schedule($users){
        // debug($users);
        // watchdog('SCH_MGR',print_r($users,true));
        $obj_config = new Config("d6");
        $grace_period_config = $obj_config::get_grace_period_config();
        $config_data = json_decode($this->obj_reminder_schedule->frequency_config,true);
        // debug($config_data);
        if(key_exists('afterHrs',$config_data)){
            $main_hrs = $grace_period_config['RESERVED']['grace_period_time_in_minutes'] / 60;
            $expiry_time = $main_hrs - $config_data['afterHrs'].' hours';
        }elseif(key_exists('afterMin',$config_data)){
            $expiry_time = $grace_period_config['RESERVED']['grace_period_time_in_minutes'] - $config_data['afterMin'].' minutes';
        }

        if($users){
            foreach($users as $user){
                $mail_mgr = new ReminderEmails();

                $tokens = array(
                    'NAME' => $user['exhibitor_name'],
                    'EXPIRY_TIME' => $expiry_time,
                );

                $mail_mgr->addTokens($tokens);

                $mail_mgr->setEmailType($this->obj_reminder_schedule->email_template_key);

                $mail_mgr->applyTokens($tokens);

                $primary_mail = $user['primary_email'];

                if ($primary_mail) {
                    $mail_mgr->setTo($primary_mail);
                }

                $additional_recipients = json_decode($this->obj_reminder_schedule->additional_recipients);

                if(is_array($additional_recipients->to) && sizeof($additional_recipients->to) > 0){
                    $mail_mgr->setTo($additional_recipients->to);
                }

                if(is_array($additional_recipients->cc) && sizeof($additional_recipients->cc) > 0){
                    $mail_mgr->setCc($additional_recipients->cc);
                }

                if(is_array($additional_recipients->bcc) && sizeof($additional_recipients->bcc) > 0){
                    $mail_mgr->setBcc($additional_recipients->bcc);
                }
                if (isset($primary_mail)) {
                    $mail_log_data = array();
                    foreach($mail_mgr as $k => $v) {
                        if($k != 'mailer'){
                            $mail_log_data[$k] = $v;
                        }
                    }
                    $logData = serialize($mail_log_data) ;

                    $obj_log_request_model = new ReminderLogRequestModel();
                    $obj_log_request_model->primary_user_ref_id = $user['exhibitor_uid'];
                    $obj_log_request_model->transaction_id = $user['transaction_id'];
                    $obj_log_request_model->schedule_id = $this->obj_reminder_schedule->id;
                    $obj_log_request_model->status = 'INIT';
                    $obj_log_request_model->sent_on = date('Y-m-d H:i:s');
                    $obj_log_request_model->log_data = $logData;

                    $log_mngr = new ReminderLogManager($this->uid,$obj_log_request_model);
                    $log_id = $log_mngr->save_log();

                    $result = $mail_mgr->send();
                    if ($result) {
                        //UPDATE LOG HERE
                        $obj_log_request_model = new ReminderLogRequestModel();
                        $obj_log_request_model->id = $log_id;
                        $obj_log_request_model->status = 'COMPLETE';
                        $log_mngr = new ReminderLogManager($this->uid,$obj_log_request_model);
                        $log_id = $log_mngr->save_log();

                    } else {
                        //UPDATE LOG HERE
                        $obj_log_request_model = new ReminderLogRequestModel();
                        $obj_log_request_model->id = $log_id;
                        $obj_log_request_model->status = 'FAILED';
                        $log_mngr = new ReminderLogManager($this->uid,$obj_log_request_model);
                        $log_id = $log_mngr->save_log();
                    }
                } else {
                    //LOG HERE
                    // return false;
                }
            }
        }

    }

}