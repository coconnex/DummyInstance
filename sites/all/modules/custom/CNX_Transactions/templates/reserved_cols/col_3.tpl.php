<?php if($is_grace_period_expired === false){ ?>
<div class="right_section_btn text-center">

<?php if ($vars['is_validated'] && $vars['row_counts']['RESERVED'] == 1){ ?>
    <input type="button" id="contract_creation_btn" onclick="check_selected_stand_for_contracts('NO')" class="common_btn mt-0" value="Book Now" /></br>
<?php } ?>

<?php $actions = $row->transaction_actions;
// debug($actions,1);
if(is_array($actions)){
    for($j = 0; $j < count($actions); $j++){
        $action = $actions[$j];
        echo $action->control->html;
    }
}
?>
</div>

<?php } elseif($is_grace_period_expired === true){
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
<?php } ?>