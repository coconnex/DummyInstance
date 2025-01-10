<div class="mystand_card_value_data m-auto">

        <input type="hidden" name="stand_ref" id="stand_ref<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->stand_ref_id; ?>" />
        <input type="hidden" name="sel_package" id="sel_package<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->additional_info->product_ref; ?>" />
        <input type="hidden" id="quantity<?php echo $row->stand_transaction_id; ?>" name="quantity" value="<?php echo $row->quantity; ?>" />
        <input type="hidden" id="stand_transaction_id<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->stand_transaction_id; ?>" />
        <input type="hidden" id="fees" name="fees" value='<?php echo json_encode($fees); ?>' />
        <input type="hidden" id="fees_total<?php echo $row->stand_transaction_id; ?>" name="fees_total" value='<?php echo $fees_total; ?>' />

        <br/>

        <div class="container">
            <div class="row">
                <div class="col-8 col-sm-9 text-end">
                    <?php   if (is_array($packages)) {
                        $total = $row->total; ?>
                        <select name="shell_scheme" id="package<?php echo $row->stand_transaction_id; ?>" form="shell_scheme" class="dropdown_space d-flex-none" onchange="updateTransaction(<?php echo $row->stand_transaction_id; ?>)" <?php echo ($is_grace_period_expired === true) ? 'disabled' : ''; ?> >
                        </select>
                    <?php } ?>
                </div>
                <div class="col-4 col-sm-3 text-end">
                    <p>£<span id="reserved_sub_total<?php echo $row->stand_transaction_id; ?>"><?php echo number_format((float)$row->total,2 ,'.', ''); ?></span></p>
                </div>
            </div>
            <?php   if (is_array($fees)) {
               foreach ($fees as $fee) {
                $total = $total + $fee['rate'] * $fee['quantity']?>
                <div class="row">
                    <div class="col-8 col-sm-9  text-end">
                        <?php echo str_replace("– For space and shell"," ",$fee['description']); ?>
                    </div>
                    <div class="col-4 col-sm-3 text-end">
                        £<?php echo $fee['rate']; ?>
                    </div>
                </div>
            <?    }
             } ?>



            <div class="verticaly_border mt-3 mb-3"></div>
            <div class="row">
                <div class="col-8 col-sm-9  text-end">
                    <p><strong><span>Price (Ex.VAT): </span></strong>​</p>
                </div>
                <div class="col-4 col-sm-3 text-end">
                    <p><strong>£</strong><strong><span id="reserved_total<?php echo $row->stand_transaction_id; ?>"><?php echo number_format((float)$total,2 ,'.', ''); ?></span></strong>​</p>
                </div>
            </div>

        </div>

</div>
<script>
    let products = JSON.parse('<?php echo $vars['products']; ?>');
</script>
