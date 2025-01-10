<?php
namespace Coconnex\API\Reminders\Schedules\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/DBFactory/Db.Class.php");
use Coconnex\DBFactory\Db;

class ReminderSchedule extends Db
{
    public $id;
    public $reminder_id;
    public $frequency;
    public $frequency_config;
    public $additional_recipients;
    public $email_template_key;
    public $is_active;
    public $last_activated_on;
    public $last_activated_by;
    public $last_deactivated_on;
    public $last_deactivated_by;

    public function __construct($uid, $id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid
                            ,'cnx_reminder_schedule'
                            ,'id'
                            ,'created_on'
                            ,'created_by'
                            ,'modified_on'
                            ,'modified_by');
        parent::__construct($id);
	}
}