<div class="contract_data">
    <div class="contract_data_left">
        <div class="d-flex contract-data-info">
            <h4 class="mt-auto mb-auto me-2"><strong><?php echo $row->customer_info->Company->name; ?></strong></h4>
            <p class="contract-submited-common-title mt-auto mb-auto">
                <?php
                    $order_status_titles = array('SUBMITTED' => 'CONTRACT_SIGNATURE_PENDING',
                    'ACCEPTED' => 'CONTRACT_SUBMITTED',
                    'CANCELLATION_APPROVED' => 'CANCELLATION_INITIATED');
                    $badge_bg =  ($row->order_status == 'CONFIRMED') ? 'bg-success' : 'bg-light text-dark';
                    $orderStatus = trim($row->order_status);
                    $order_status = (array_key_exists($orderStatus,$order_status_titles)) ? $order_status_titles[$orderStatus]: $orderStatus;
                ?>
                <span class="text-wrap mt-1 badge bg-light text-dark<?php echo $badge_bg; ?>"><?php echo ucwords(strtolower(str_replace("_", " ", $order_status))); ?></span>
            </p>
        </div>
        <div class="d-flex mt-3 mt-md-4 mb-md-3">
            <div>
                <p class="mb-0 mt-0 label_value_pair <?php echo $txt_class; ?>"><span>Contract No: </span> <span>WEB-TD-<?php echo $row->order_id; ?></span></p>
                <p class="mb-0 mt-0 label_value_pair <?php echo $txt_class; ?>"><span>Dated: </span><span><?php echo $row->order_date; ?></span></p>
                <p class="mb-0 mt-0 label_value_pair <?php echo $txt_class; ?>"><span>Type: </span><span>Stand Booking</span></p>
            </div>

        </div>
    </div>
</div>