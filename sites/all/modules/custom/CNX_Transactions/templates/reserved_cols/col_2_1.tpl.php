
<?php
// debug($row);
$packages = json_decode($vars['products'],true);
$packages = $packages['DATA'];
// debug($packages,1);
$fees = array();
if(is_array($packages)){
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
            $fees[] = $fee_item;
        }
    }
}
if(is_array($packages)){ ?>
    <select name="shell_scheme" id="package<?php echo $row->stand_transaction_id; ?>" form="shell_scheme" class="dropdown_space" onchange="updateTransaction(<?php echo $row->stand_transaction_id; ?>)">
    </select>
<?php } ?>
<input type="hidden" name="stand_ref" id="stand_ref<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->stand_ref_id; ?>" />
<input type="hidden" name="sel_package" id="sel_package<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->additional_info->product_ref; ?>" />
<input type="hidden" id="quantity<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->quantity; ?>" />
<input type="hidden" id="stand_transaction_id<?php echo $row->stand_transaction_id; ?>" value="<?php echo $row->stand_transaction_id; ?>" />
<input type="hidden" id="fees" name="fees" value='<?php echo json_encode($fees); ?>' />
<p class="mystand_card_value mt-3">​Rate £<span id="reserved_rate<?php echo $row->stand_transaction_id; ?>"><?php echo number_format($row->rate); ?></span> / M<sup>2</sup></p>
<script>
   let products = JSON.parse('<?php echo $vars['products']; ?>');
</script>