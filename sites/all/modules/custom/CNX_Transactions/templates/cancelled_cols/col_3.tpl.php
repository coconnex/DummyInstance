
<div class="right_section_cancel_info p-3 rounded-1 w-100  mt-auto mb-auto">
                <p class="mb-0">Cancellation Details</p>
                <?php

 $date = date_create($row->cancelled_on);
                      $cancelled_date = date_format($date, "d-M-Y");?>
                <p class="label_value_pair mb-0 waitlisted_text_name"><span>Cancelled on: </span><span><?php echo $cancelled_date; ?></span></p>
                <p class="waitlisted_text_name label_value_pair"><span>Cancelled by: </span><span><?php echo $row->cancelled_by; ?></span></p>
                <?php if(!empty($row->reason)){ ?>
                <div class="right_section_cancel_reason rounded-1 p-2">
                    <input type="hidden" id="submitted_cancellation_reason<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->reason; ?>" />
                    <p class="waitlisted_text label_value_pair mb-0"><span>Reason: </span><span class="d-block">
                        <?php echo $obj_config::string_wrap($row->reason,$row->stand_transaction_id); ?></span></p>
                </div>
                <?php } ?>

        </div>