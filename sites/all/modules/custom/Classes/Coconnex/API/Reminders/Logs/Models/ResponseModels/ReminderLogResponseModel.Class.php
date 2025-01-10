<?php
namespace Coconnex\API\Reminders\Logs\Models\ResponseModels;

class ReminderLogResponseModel
{
    public $id;
    public $primary_user_ref_id;
    public $transaction_id;
    public $schedule_id;
    public $status;
    public $sent_on;
}