<div class="contract_data container">
    <div class="row contract_data_left">
        <?php
            include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_1_1.tpl.php";
            if($is_timer_active && $row->status == 'CONTRACT_SIGNATURE_PENDING'){
                include $vars['template_base_path'] . $col_fld_prefix . "_cols/col_1_2.tpl.php";
            }
        ?>
        <?php if (!empty($floorplan_status_msg)) {
        ?>
            <div class="reserved_floor_plan_info mt-3">
                <i class="fas fa-circle-info"></i>
                <div><?php echo $floorplan_status_msg; ?></div>
            </div>
        <?php } ?>
    </div>
</div>