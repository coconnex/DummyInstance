<div class="contract_data">
    <div class="contract_data_left">
        <div class="d-flex contract-data-info">
            <h4 class="contract-data-header mt-auto mb-auto me-2"><strong><del><?php echo $row->additional_info->stand_no; ?></del></strong></h4>
            <p class="contract-submited-common-title mt-auto mb-auto">
                <span class="badge mt-1 text-wrap">Cancelled​</span>
            </p>
        </div>
        <div class="mt-3 mt-md-4 mb-md-3">
            <p class="mb-0 mt-0 label_value_pair"><del><span>Area: </span><span><?php echo $row->quantity; ?> m<sup>2</sup></span></del></p>
            <?php if ($row->additional_info->stand_opensides > 0) { ?>
                <p class="mb-0 mt-0 label_value_pair"><del><span>Open Sides: </span><span><?php echo $row->additional_info->stand_opensides; ?></span></del></p>
            <?php } ?>
            <?php
            if (isset($row->additional_info->stand_dims)) {
                $dims = $row->additional_info->stand_dims;
                $_dims = explode(",", $dims);
                $dims_disp = "";
                if (sizeof($_dims) > 1) {
                    $dims_disp = implode("m X ", $_dims) . "m";
            ?>
                    <p class="mb-0 mt-0 label_value_pair"><del><span>Dimensions: </span><span>
                            <?php echo $dims_disp; ?>
                        </span></del></p>
            <? }
            } ?>
            <?php if ($row->additional_info->stand_height > 0) { ?>
                <p class="mb-0 mt-0 label_value_pair"><del>Build Height Limits: <?php echo $row->additional_info->stand_height; ?>m </del></p>
            <?php } ?>
        </div>
    </div>

</div>