<div class="right_section_btn">
    <!-- <button class="contract_btn">Download Contract
        WEB-TD-3002</button><br> -->
    <!-- <button class="Cancel_btn" onclick="request_cancellation('ID_CNT_REQ_CANCEL24')">Request Cancellation</button><br> -->

    <?php if ($row->status == 'CANCELLATION_REQUESTED' || $row->status == 'CANCELLATION_INITIATED') {
        $cancellation_requested_date = strtotime($row->contract_cancellation_requested_on);
        $cancellation_requested_date = date('d-M-Y', $cancellation_requested_date); ?>

        <div class="right_section_cancel_info p-3 rounded-1 w-100">
                <p class="mb-0">Cancellation Details</p>
                <p class="label_value_pair mb-0 waitlisted_text_name"><span>Requested on: </span><span><?php echo $cancellation_requested_date; ?></span></p>
                <p class="waitlisted_text_name label_value_pair"><span>Requested by: </span><span><?php echo $row->contract_cancellation_requested_by; ?></span></p>
                <?php if(!empty($row->reason)){ ?>
                <div class="right_section_cancel_reason rounded-1 p-2">
                    <input type="hidden" id="submitted_cancellation_reason<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->reason; ?>" />
                    <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span><span class="d-block">
                    <?php echo $obj_config::string_wrap($row->reason,$row->stand_transaction_id); ?>
                    </span></p>
                </div>
                <?php } ?>
        </div>

    <?php }elseif($is_grace_period_expired === true){
     $expiry_date = date('d-M-Y', $expiry_date);
     ?>
<div class="right_section_cancel_info p-3 rounded-1 w-100  mt-auto mb-auto">
    <p class="mb-0">Reservation expired</p>
    <p class="label_value_pair mb-0 waitlisted_text_name"><span>Cancelled on: </span><span><?php echo $expiry_date; ?></span></p>
    <p class="waitlisted_text_name label_value_pair"><span>Cancelled by: </span><span><?php echo 'System'; ?></span></p>
    <div class="right_section_cancel_reason rounded-1 p-2">
        <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span>
        <span class="d-block">
            <?php //echo $obj_config::string_wrap($row->reason,$row->stand_transaction_id); ?>
            Your reservation has expired.
        </span></p>
    </div>
</div>
<?php }else{ ?>
    <?php $actions = $row->transaction_actions;
    // debug($actions,1);
    if (is_array($actions)) {
        for ($j = 0; $j < count($actions); $j++) {
            $action = $actions[$j];
            $action->control->styles = "common_btn";
            echo $action->control->get_html() . '';
        }
    }
}
    ?>
</div>
