<div class="<?php echo $first_col; ?>">
    <div class="contract_data_left  m-auto">

        <div class="d-flex contract-data-info">
            <?php if ($vars['is_validated']) { ?>
                <h4 class="contract-data-header mt-auto mb-auto me-2"><strong><?php echo $row->additional_info->stand_no; ?></strong></h4>
            <?php } ?>
            <p class="contract-submited-common-title mt-auto mb-auto"><span class="mt-1 text-wrap badge bg-light text-dark"><?php echo ucwords(strtolower(str_replace("_", " ", $row->status))); ?></p>
        </div>

        <div class="d-flex mt-3 mt-md-4 mb-md-3">
            <div>
                <p class="mb-0 mt-0 label_value_pair"><span>Area: </span><span> <?php echo $row->quantity; ?> m<sup>2</sup></span></p>
                <?php if ($row->additional_info->stand_opensides > 0) { ?>
                    <p class="mb-0 mt-0 label_value_pair"><span>Open Sides: </span><span><?php echo $row->additional_info->stand_opensides; ?></span></p>
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
                    <p class="mb-0 mt-0 label_value_pair"><span>Build Height Limits: </span><span><?php echo $row->additional_info->stand_height; ?>m </span></p>
                <?php } ?>
            </div>
        </div>

        <?php
        $floorplan_status_msg = "";
        $previous_status = $row->previous_status;
        switch ($row->status) {
            case 'CONTRACT_SIGNATURE_PENDING':
            case 'CONTRACT_SUBMITTED':
                $floorplan_status_msg = "Shown as RESERVED on floor plan";
                break;
            case 'CONTRACT_COMPLETED':
                $floorplan_status_msg = "Shown as BOOKED on floor plan";
                break;
            case 'CANCELLATION_REQUESTED':
                if ($previous_status == 'CONTRACT_SUBMITTED' || $previous_status == 'CONTRACT_ACCEPTED') {
                    $floorplan_status_msg = "Shown as RESERVED on floor plan";
                } elseif ($previous_status == 'CONTRACT_COMPLETED') {
                    $floorplan_status_msg = "Shown as BOOKED on floor plan";
                }
                break;
            case 'CANCELLATION_INITIATED':
                if ($previous_status == 'CONTRACT_SUBMITTED' || $previous_status == 'CONTRACT_ACCEPTED') {
                    $floorplan_status_msg = "Shown as RESERVED on floor plan";
                } elseif ($previous_status == 'CONTRACT_COMPLETED') {
                    $floorplan_status_msg = "Shown as BOOKED on floor plan";
                }
                break;
            default:
                $floorplan_status_msg = "";
                break;
        }
        ?>
    </div>

</div>

