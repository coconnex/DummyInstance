  <div class="mystand_card_value_data m-auto w-100">
    <br/>
    <div class="container">
        <div class="row">
            <div class="col-8 text-end">
                <p><del><span><?php echo $row->description; ?></span></del></p>
            </div>
            <div class="col-4 text-end">
                <p><del>£<span><?php echo number_format((float)$row->total,2 ,'.', ''); ?></span></del></p>
            </div>
        </div>
        <?php
            $total = $row->total;
            if (is_array($fees)) {
            foreach ($fees as $fee) {
            $total = $total + $fee['rate'] * $fee['quantity']?>
            <div class="row">
                <div class="col-8 text-end">
                  <del><?php echo str_replace("– For space and shell"," ",$fee['description']); ?></del>
                </div>
                <div class="col-4 text-end">
                  <del>£<?php echo $fee['rate']; ?></del>
                </div>
            </div>
        <?    }
            } ?>

        <div class="verticaly_border mt-3 mb-3"></div>
        <div class="row">
            <div class="col-8 text-end">
                <p><del><strong><span>Price (Ex.VAT): </span></strong></del>​</p>
            </div>
            <div class="col-4 text-end">
                <p><del><strong>£</strong><strong><span><?php echo number_format((float)$total,2 ,'.', ''); ?></span></strong></del>​</p>
            </div>
        </div>
    </div>
</div>
