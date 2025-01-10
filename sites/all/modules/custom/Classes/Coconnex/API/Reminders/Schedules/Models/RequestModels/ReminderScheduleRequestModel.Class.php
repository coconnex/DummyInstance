<?php
namespace Coconnex\API\Reminders\Schedules\Models\RequestModels;

class ReminderScheduleRequestModel
{
    public $id;
    public $reminder_id;
    public $frequency;
    public $frequency_config;
    public $additional_recipients;
    public $email_template_key;
    public $last_activated_on;
    public $last_activated_by;
    public $last_deactivated_on;
    public $last_deactivated_by;
}
