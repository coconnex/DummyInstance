<?php
namespace Coconnex\API\Reminders\Schedules\Collection;

class ReminderScheduleList{
    public $uid;
    public $reminder_id;
    public $all_active_schedules;

    public function __construct($uid, $reminder_id)
    {
        $this->uid = $uid;
        $this->reminder_id = $reminder_id;
        if (is_numeric($reminder_id) && $reminder_id > 0) $this->all_active_schedules = $this->get_schedules();
    }

    public function get_schedules()
    {


    }
}