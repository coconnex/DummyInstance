<?php
namespace Coconnex\API\Reminders\Logs\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/DBFactory/Db.Class.php");
use Coconnex\DBFactory\Db;

class ReminderLogs extends Db
{
    public $id;
    public $primary_user_ref_id;
    public $transaction_id;
    public $schedule_id;
    public $status;
    public $sent_on;

    public function __construct($uid, $id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid
                            ,'cnx_reminder_logs'
                            ,'id'
                            ,'created_on'
                            ,'created_by'
                            ,'modified_on'
                            ,'modified_by');
        parent::__construct($id);
	}
}