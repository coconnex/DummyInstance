<div class="<?php echo $first_col; ?>">
                <div class="d-flex contract-data-info">
                    <?php if ($vars['is_validated']) { ?>
                        <h4 class="contract-data-header mt-auto mb-auto me-2"><strong><?php echo $row->additional_info->stand_no; ?></strong></h4>
                    <?php } ?>
                    <p class="contract-submited-common-title mt-auto mb-auto"><span class="mt-1 text-wrap badge bg-light text-dark"><?php echo ucwords(strtolower(str_replace("_", " ", $row->status))); ?></p>
                </div>
                <div class="d-flex mt-3 mt-md-4 mb-md-3">
                    <div>
                        <p class="mb-0 mt-0 label_value_pair"><span>Area: </span> <span><?php echo $row->quantity; ?> m<sup>2</sup></span></p>
                        <?php if ($row->additional_info->stand_opensides > 0) { ?>
                            <p class="mb-0 mt-0 label_value_pair">Open Sides: <span><?php echo $row->additional_info->stand_opensides; ?></span></p>
                        <?php } ?>
                        <?php
                        if (isset($row->additional_info->stand_dims)) {
                            $dims = $row->additional_info->stand_dims;
                            $_dims = explode(",", $dims);
                            $dims_disp = "";
                            if (sizeof($_dims) > 1) {
                                $dims_disp = implode("m X ", $_dims) . "m";
                        ?>
                                <p class="mb-0 mt-0 label_value_pair">Dimensions: <span>
                                        <?php echo $dims_disp; ?>
                                    </span></p>
                        <? }
                        } ?>
                        <?php if ($row->additional_info->stand_height > 0) { ?>
                            <p class="mb-0 mt-0 label_value_pair"> Build Height Limits: <span><?php echo $row->additional_info->stand_height; ?></span>m</p>
                        <?php } ?>

                    </div>
                </div>
            </div>