<?php
namespace Coconnex\API\Reminders\Collection;

class ReminderList{
    public $uid;
    public $reminder_id;
    public $all_active_reminders;

    public function __construct($uid, $reminder_id)
    {
        $this->uid = $uid;
        $this->reminder_id = $reminder_id;
        if (is_numeric($reminder_id) && $reminder_id > 0) $this->all_active_reminders = $this->get_reminders();
    }

    public function get_reminders()
    {


    }
}