<?php
 if ($vars['is_validated'] && $vars['row_counts']['RESERVED'] > 1) { ?>
    <input class="mt-2" type="checkbox" id="stand_row_check" name="stand_transaction_id[]" value="<?php echo $row->stand_transaction_id; ?>">
<?php } elseif ($vars['is_validated'] && $vars['row_counts']['RESERVED'] == 1) { ?>
    <input class="mt-2" type="hidden" id="stand_row_check" name="stand_transaction_id[]" value="<?php echo $row->stand_transaction_id; ?>">
<?php } ?>

<div class="contract_data container">
    <div class="row contract_data_left">
        <?php
            include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_1_1.tpl.php";
            if($is_timer_active){
                include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_1_2.tpl.php";
            }
        ?>
    </div>
</div>