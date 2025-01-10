<?php

use Coconnex\API\IFPSS\StandTransaction\Managers\StandTransactionManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Order\Managers\APIOrderManager;

switch ($vars['status']) {
    case 'RESERVED':
        $bg_class = "reserve_bg";
        $col_fld_prefix = "reserved";
        $col_breakpoint="col-md-4 col-lg-4 col-xl-4";
        $packages = json_decode($vars['products'], true);
        $packages = $packages['DATA'];
        $fees = array();
        if (is_array($packages)) {
            $fees_total = 0;
            $fees = array();
            foreach ($packages as $package) {
                if ($package['package_type'] == 'FEE') {
                    $fee_item = array(
                        'product_ref' => $package['urn'],
                        'stand_ref_id' => 0,
                        'description' => $package['description'],
                        'quantity' => 1,
                        'rate' => $package['unit_price'],
                        'stand_number' => '',
                        'item_type' => 'FEES'
                    );
                    $fees_total = $fees_total + $package['unit_price'];
                    $fees[] = $fee_item;
                }
            }
        }

        $is_grace_period_expired = false;
        $is_timer_active = false;
        $first_col = 'col-12';

If ($is_graceperiod_active == 1  && $is_grace_period_expired == false && $grace_period_config['RESERVED']['is_grace_period_active'] == 'YES') {
            $reserved_date = strtotime($row->reserved_on);
            $reserved_grace_minutes = $row->reserved_grace_minutes;
            $expiry_date = strtotime("+" . $reserved_grace_minutes . " minutes", $reserved_date);

            if((date('Y-m-d H:i:s', $expiry_date) < date('Y-m-d H:i:s'))){
                $is_grace_period_expired = true;
                $bg_class = "waiting_bg";
            }else{
                $is_timer_active = true;
                $first_col = 'col-6 col-xxl-7';
            }
        }

        $transaction_mgr = new StandTransactionManager($user->uid,$user->roles,$row->stand_transaction_id);
        $intervals = $transaction_mgr->get_transaction_remaining_time('RESERVED',array());

        break;
    case 'BOOKED':
        $bg_class = "booked_bg";
        $col_fld_prefix = "booked";
        $col_breakpoint="col-md-4 col-lg-4 col-xl-4";
        $api_order_mgr = new APIOrderManager($row->external_ref_id, 'order_items_get');
        $api_order_mgr->get_order_items();
        $response = json_decode($api_order_mgr->order_response);
        $order_items = $response->data;
        if (is_array($order_items)) {
            $fees_total = 0;
            $fees = array();
            foreach ($order_items as $items) {
                if ($items->item_type == 'FEES') {
                    $fee_item = array(
                        'product_ref' => $items->order_item_id,
                        'stand_ref_id' => 0,
                        'description' => $items->description,
                        'quantity' => $items->quantity,
                        'rate' => $items->unit_price,
                        'stand_number' => '',
                        'item_type' => 'FEES'
                    );
                    $fees_total = $fees_total + $items->unit_price;
                    $fees[] = $fee_item;
                }
            }
        }

        $is_grace_period_expired = false;
        $is_timer_active = false;
        $first_col = 'col-12';

        if ($is_graceperiod_active == 1  && $is_grace_period_expired == false && $grace_period_config['RESERVED']['is_grace_period_active'] == 'YES' && $row->status == 'CONTRACT_SIGNATURE_PENDING') {
            $reserved_date = strtotime($row->reserved_on);
            $reserved_grace_minutes = $row->reserved_grace_minutes;
            $expiry_date = strtotime("+" . $reserved_grace_minutes . " minutes", $reserved_date);

            if((date('Y-m-d H:i:s', $expiry_date) < date('Y-m-d H:i:s'))){
                $is_grace_period_expired = true;
                $bg_class = "waiting_bg";
            }else{
                $is_timer_active = true;
                $first_col = 'col-7 col-md-6 col-xl-7';
            }
        }

        $transaction_mgr = new StandTransactionManager($user->uid,$user->roles,$row->stand_transaction_id);
        $intervals = $transaction_mgr->get_transaction_remaining_time('RESERVED',array());

        break;
    case 'CANCELLED':
        $bg_class = "waiting_bg";
        $col_fld_prefix = "cancelled";
        $col_breakpoint="col-md-4 col-lg-4 col-xl-4";
        $api_order_mgr = new APIOrderManager($row->external_ref_id, 'order_items_get');
        $api_order_mgr->get_order_items();
        $response = json_decode($api_order_mgr->order_response);
        $order_items = $response->data;
        if (is_array($order_items)) {
            $fees_total = 0;
            $fees = array();
            foreach ($order_items as $items) {
                if ($items->item_type == 'FEES') {
                    $fee_item = array(
                        'product_ref' => $items->order_item_id,
                        'stand_ref_id' => 0,
                        'description' => $items->description,
                        'quantity' => $items->quantity,
                        'rate' => $items->unit_price,
                        'stand_number' => '',
                        'item_type' => 'FEES'
                    );
                    $fees_total = $fees_total + $items->unit_price;
                    $fees[] = $fee_item;
                }
            }
        }
        break;
    case 'WAITLISTED':
        $bg_class = "waiting_bg";
        $col_fld_prefix = "waitlisted";
        break;
}


?>
<div class="row box_shadow rounded-1 mb-3">
    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 d-flex <?php echo $bg_class; ?> rounded-1">
        <?php include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_1.tpl.php"; ?>
    </div>
    <div class="col-sm-12 <?php echo  $col_breakpoint; ?> d-flex mt-3 mt-md-0">
        <?php include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_2.tpl.php"; ?>
    </div>
    <div class="col-sm-12 <?php echo  $col_breakpoint; ?> d-flex label_btn_section p-0  my-3 mt-md-0 mb-md-0">
        <?php include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_3.tpl.php"; ?>
    </div>
</div>