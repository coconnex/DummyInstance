<div class="mystand_card_value_data m-auto w-100">
<br/>
    <div class="container">
        <div class="row">
            <div class="col-8 text-end">
                <p><span><?php echo $row->description; ?></span></p>
            </div>
            <div class="col-4 text-end">
                <p>£<span><?php echo number_format((float)$row->total,2 ,'.', ''); ?></span></p>
            </div>
        </div>
        <?php
            $total = $row->total;
            if (is_array($fees)) {
            foreach ($fees as $fee) {
            $total = $total + $fee['rate'] * $fee['quantity']?>
            <div class="row">
                <div class="col-8 text-end">
                    <?php echo str_replace("– For space and shell"," ",$fee['description']); ?>
                </div>
                <div class="col-4 text-end">
                    £<?php echo $fee['rate']; ?>
                </div>
            </div>
        <?    }
            } ?>

        <div class="verticaly_border mt-3 mb-3"></div>
        <div class="row">
            <div class="col-8 text-end">
                <p><strong><span>Price (Ex.VAT): </span></strong>​</p>
            </div>
            <div class="col-4 text-end">
                <p><strong>£</strong><strong><span><?php echo number_format((float)$total,2 ,'.', ''); ?></span></strong>​</p>
            </div>
        </div>
    </div>
</div>