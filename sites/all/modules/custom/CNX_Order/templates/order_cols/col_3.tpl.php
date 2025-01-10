<?php if ($is_grace_period_expired === false) { ?>
    <div class="right_section_btn">
        <?php
        global $user;
        $user_info = user_load($user->uid);
        $user_name = $user_info->profile_first_name . ' ' . $user_info->profile_last_name;

        $date = date_create($row->cancelled_on);
        $cancelled_date = date_format($date, "d-M-Y");
        $actions = "";
        switch ($row->order_status) {
            case 'SUBMITTED':
                $actions =  '<button class="common_btn p-2 mt-3" type="button" id="ID_CNT_SIGN' . $stand_transaction_id . '" value="' . $stand_transaction_id . '" link="/mystands/action/CNT_SIGN" onclick="manage_actions(\'ID_CNT_SIGN' . $stand_transaction_id . '\')">Sign Contract</button>
            
                <button class="common_btn order_canceled_req_btn p-2 mt-2 mb-2" type="button" id="ID_ORD_REQ_CANCEL' . $row->order_id . '" value="' . $row->order_id . '" link="/mystands/action/ORD_REQ_CANCEL" onclick="request_cancellation(\'ID_ORD_REQ_CANCEL' . $row->order_id . '\')">Request Cancellation</button>';
                break;
            case 'ACCEPTED':
                $actions = '<button class="common_btn p-2 mt-2" type="button" id="ID_ORD_PDF_DNWD' . $row->order_id . '" value="' . $row->order_id . '" link="/mystands/action/ORD_PDF_DNWD" onclick="manage_actions(\'ID_ORD_PDF_DNWD' . $row->order_id . '\')">Download Contract</button>
                <button class="common_btn order_canceled_req_btn p-2 mt-2 mb-2" type="button" id="ID_ORD_REQ_CANCEL' . $row->order_id . '" value="' . $row->order_id . '" link="/mystands/action/ORD_REQ_CANCEL" onclick="request_cancellation(\'ID_ORD_REQ_CANCEL' . $row->order_id . '\')">Request Cancellation</button>';
                break;
            case 'CONFIRMED':
                $actions = '<button class="common_btn p-2 mt-2" type="button" id="ID_ORD_PDF_DNWD' . $row->order_id . '" value="' . $row->order_id . '" link="/mystands/action/ORD_PDF_DNWD" onclick="manage_actions(\'ID_ORD_PDF_DNWD' . $row->order_id . '\')">Download Contract</button>
                <button class="common_btn order_canceled_req_btn p-2 mt-2 mb-2" type="button" id="ID_ORD_REQ_CANCEL' . $row->order_id . '" value="' . $row->order_id . '" link="/mystands/action/ORD_REQ_CANCEL" onclick="request_cancellation(\'ID_ORD_REQ_CANCEL' . $row->order_id . '\')">Request Cancellation</button>';
                break;
            case 'CANCELLATION_REQUESTED':
                $actions = "";
                break;
            case 'CANCELLED':
                $actions = "";
                break;
            default:
                $actions = "";
                break;
        }

        if (!empty($actions)) {
            echo $actions;
        }
        ?>

        <?php if ($row->order_status == 'CANCELLED') { ?>
            <div class="right_section_cancel_info p-3 rounded-1 w-100">
                <p class="label_value_pair mb-0 waitlisted_text_name">Contract has been cancelled on: <?php echo $cancelled_date; ?></p>
                <p class="waitlisted_text_name label_value_pair"><span>Cancelled by: </span><span><?php echo $row->cancelled_by; ?></span></p>
                <?php if (!empty($row->reason)) { ?>
                    <div class="right_section_cancel_reason rounded-1 p-2">
                        <input type="hidden" id="submitted_cancellation_reason<?php echo $row->order_id; ?>" value="<?php echo $row->reason; ?>" />
                        <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span><span class="d-block">
                                <?php echo $obj_config::string_wrap($row->reason, $row->order_id); ?>
                            </span></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($row->order_status == 'CANCELLATION_REQUESTED') { ?>
            <div class="right_section_cancel_info p-3 rounded-1 w-100">
                <p class="label_value_pair mb-0 waitlisted_text_name">Contract cancellation requested on: <?php echo $row->cancellation_on; ?></p>
                <p class="waitlisted_text_name label_value_pair"><span>Requested by: </span><span><?php echo $user_name; ?></span></p>
                <?php if (!empty($row->reason)) { ?>
                    <div class="right_section_cancel_reason rounded-1 p-2">
                        <input type="hidden" id="submitted_cancellation_reason<?php echo $row->order_id; ?>" value="<?php echo $row->reason; ?>" />
                        <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span><span class="d-block">
                                <?php echo $obj_config::string_wrap($row->reason, $row->order_id); ?>
                            </span></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($row->order_status == 'CANCELLATION_APPROVED') { ?>
            <div class="right_section_cancel_info p-3 rounded-1 w-100">
                <p class="label_value_pair mb-0 waitlisted_text_name">Cancellation Details</p>
                <p class="label_value_pair mb-0 waitlisted_text_name"><span>Requested on: </span><span><?php echo $row->cancellation_on; ?></span></p>
                <p class="waitlisted_text_name label_value_pair"><span>Requested by: </span><span><?php echo $user_name; ?></span></p>
                <?php if (!empty($row->reason)) { ?>
                    <div class="right_section_cancel_reason rounded-1 p-2">
                        <input type="hidden" id="submitted_cancellation_reason<?php echo $row->order_id; ?>" value="<?php echo $row->reason; ?>" />
                        <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span><span class="d-block">
                                <?php echo $obj_config::string_wrap($row->reason, $row->order_id); ?>
                            </span></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

<?php } elseif ($is_grace_period_expired === true) {
    $expiry_date = date('d-M-Y', $expiry_date);
?>
    <div class="right_section_cancel_info p-3 rounded-1 w-100  mt-auto mb-auto">
        <p class="mb-0">Reservation expired</p>
        <p class="label_value_pair mb-0 waitlisted_text_name"><span>Cancelled on: </span><span><?php echo $expiry_date; ?></span></p>
        <p class="waitlisted_text_name label_value_pair"><span>Cancelled by: </span><span><?php echo 'System'; ?></span></p>
        <div class="right_section_cancel_reason rounded-1 p-2">
            <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span>
                <span class="d-block">
                    <?php //echo $obj_config::string_wrap($row->reason,$row->stand_transaction_id);
                    ?>
                    Your reservation has expired.
                </span>
            </p>
        </div>
    </div>
<?php } ?>