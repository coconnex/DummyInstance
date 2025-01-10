<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//$new_user_id = 16102;
//$new_user = user_load($new_user_id);
 //_user_mail_notify('password_reset', $new_user);
//die;
//echo 'In Schedule Execution';
$schedule_id = $_GET['schedule_id'];
//$schedule_id = 19;
//echo $schedule_id;
watchdog('EXESCH',print_r($_GET,true));
watchdog('EXESCH',$schedule_id);

CNX_Reminder_schedule_execute($schedule_id);

//use Coconnex\API\Reminders\Schedules\Managers\ReminderScheduleManager;

//require_once(dirname(__DIR__). "/imcem2024/sites/all/modules/custom/Classes/Coconnex/API/Reminders/Schedules/Managers/ReminderScheduleManager.Class.php");

//$schedule_mngr = new ReminderScheduleManager($user->uid,$schedule_id);
?>



