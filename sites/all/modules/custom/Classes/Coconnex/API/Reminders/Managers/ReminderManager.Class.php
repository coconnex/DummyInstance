<?
namespace Coconnex\API\Reminders\Managers;

require_once(dirname(dirname(__FILE__)) . "/Email/ReminderEmails.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/Reminder.Class.php");


use Coconnex\API\Reminders\Email\ReminderEmails;
use Coconnex\API\Reminders\Models\EntityModels\Reminder;

class ReminderManager extends ReminderEmails{

    public $uid;
    public $obj_reminder;

    public function __construct($uid,$reminder)
    {
        $this->uid = $uid;
        if (is_numeric($reminder) && $reminder > 0) $this->obj_reminder = new Reminder($uid,$reminder);

        parent::__construct();
    }

    public function get_reminders(){

    }


}