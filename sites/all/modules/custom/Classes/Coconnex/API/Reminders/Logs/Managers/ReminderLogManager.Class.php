<?
namespace Coconnex\API\Reminders\Logs\Managers;

require_once(dirname(dirname(__FILE__)) .'/Models/EntityModels/ReminderLogs.Class.php');
require_once(dirname(dirname(__FILE__)) .'/Models/RequestModels/ReminderLogRequestModel.Class.php');
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/DBFactory/Db.Class.php");

use Coconnex\API\Reminders\Logs\Models\EntityModels\ReminderLogs;
use Coconnex\API\Reminders\Logs\Models\RequestModels\ReminderLogRequestModel;
use Coconnex\DBFactory\Db;

class ReminderLogManager{

    public $uid;
    public $obj_reminder_log;

    public function __construct($uid,$reminder_log)
    {
        $this->uid = $uid;
        if ($reminder_log instanceof ReminderLogRequestModel || $reminder_log instanceof ReminderLogs) $this->obj_reminder_log = $reminder_log;
        if (is_numeric($reminder_log) && $reminder_log > 0) $this->obj_reminder_log = new ReminderLogs($uid,$reminder_log);
    }

    public function save_log()
    {
        if ($this->obj_reminder_log instanceof ReminderLogRequestModel) {

            $entity = new ReminderLogs($this->uid, $this->obj_reminder_log->id);
            if (isset($this->obj_reminder_log->id)) $entity->id  = $this->obj_reminder_log->id;
            if (isset($this->obj_reminder_log->primary_user_ref_id)) $entity->primary_user_ref_id  = $this->obj_reminder_log->primary_user_ref_id;
            if (isset($this->obj_reminder_log->transaction_id)) $entity->transaction_id  = $this->obj_reminder_log->transaction_id;
            if (isset($this->obj_reminder_log->schedule_id)) $entity->schedule_id  = $this->obj_reminder_log->schedule_id;
            if (isset($this->obj_reminder_log->status)) $entity->status  = $this->obj_reminder_log->status;
            if (isset($this->obj_reminder_log->sent_on)) $entity->sent_on  = $this->obj_reminder_log->sent_on;

            $result = $entity->save();
            if ($result > 0) {
                if (isset($this->obj_reminder_log->log_data)){
                    $arrParam['log_id'] = $result;
                    $arrParam['data'] = $this->obj_reminder_log->log_data;

                    $dmObj = new Db();
                    $id = $dmObj->InsertRecord($arrParam, "cnx_reminder_log_data");
                }
            }
            return $result;
        }
    }

    public function get_request_model($data){
        $obj_log_request_model = new ReminderLogRequestModel();
    }
}