<?php

use Coconnex\API\IFPSS\StandTransaction\Managers\StandTransactionManager;
use Coconnex\Utils\Config\Config;

$bg_class = ($row->order_status != 'CANCELLATION_REQUESTED' && $row->order_status != 'CANCELLED') ? "bg-black" : "bg-gray";
$txt_class = ($row->order_status != 'CANCELLATION_REQUESTED' && $row->order_status != 'CANCELLED') ? "text-white" : "";
$col_fld_prefix = "order";

$stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, '');
$stand_transaction_id = $stand_transaction_manager->get_transaction_id($row->order_id);

$stand_transaction_manager = new StandTransactionManager($user->uid, $user->roles, $stand_transaction_id);
$stand_transaction_obj = $stand_transaction_manager->get_stand_transaction();

$obj_config = new Config("d6");
$is_graceperiod_active = $obj_config::getvar("IS_GRACEPERIOD");
$grace_period_config = $obj_config::get_grace_period_config();

$is_grace_period_expired = false;
$is_timer_active = false;
$first_col = 'col-12';

if ($is_graceperiod_active == 1  && $is_grace_period_expired == false && $grace_period_config['RESERVED']['is_grace_period_active'] == 'YES' && $row->order_status == 'SUBMITTED') {
    $reserved_date = strtotime($stand_transaction_obj->reserved_on);
    $reserved_grace_minutes = $stand_transaction_obj->reserved_grace_minutes;
    $expiry_date = strtotime("+" . $reserved_grace_minutes . " minutes", $reserved_date);

    if((date('Y-m-d H:i:s', $expiry_date) < date('Y-m-d H:i:s'))){
        $is_grace_period_expired = true;
        $bg_class = "waiting_bg";
        $txt_class = "";
    }
}

?>
<div class="row box_shadow rounded-1 mb-3">
    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 <?php echo $bg_class; ?> <?php echo $txt_class; ?> rounded-1">
        <?php include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_1.tpl.php"; ?>
    </div>
    <div class="col-sm-12 col-md-4 col-lg-5 col-xl-5 d-flex mt-3 text-center flex-md-column flex-lg-row my-md-3 my-lg-0">
        <?php include $vars['template_base_path'] . "common_cols/col_2.tpl.php"; ?>
    </div>
    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3 d-flex label_btn_section p-0  my-3 mt-md-0 mb-md-0">
        <?php include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_3.tpl.php"; ?>
    </div>
</div>
