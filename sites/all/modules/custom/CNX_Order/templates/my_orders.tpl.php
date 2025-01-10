<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/Utils/Handlers/AssetHandler.Class.php");

use Coconnex\Utils\Config\Config;
use Coconnex\Utils\Handlers\AssetHandler;


$obj_config = new Config("d6");
AssetHandler::add_asset("/assetscdn/transactions/scripts/transactionsController.js", "script");

echo "<h5 class='common_spaceing heading_color'>My Orders</h5>";
// debug($vars['confirmed_orders'],1);
// $accepted_rows = $vars['accepted_orders'];
// $confirmed_rows = $vars['confirmed_orders'];
$all_orders_rows = $vars['all_orders'];
$cancelled_orders_rows = $vars['cancelled_orders'];
?>

<div class="order">
    <!--******************************* Orders **********************************-->

    <div id="orders_container" class="order-tabcontent p-4 p-md-5 mx-0 mx-md-4">
        <div class="rounded-1 mb-3">
            <?php
            if (sizeof($all_orders_rows) > 0) {
                for ($i = 0; $i < sizeof($all_orders_rows); $i++) {
                    $row = $all_orders_rows[$i];
                    include $vars['row_template_path'];
                    $row = null;
                }
                $i = null;
            } else {
                echo '<br/><div style="text-align: center;" ><h3>No records found.</h3></div>';
            }
            ?>
        </div>
    </div>
    <!--******************************* Orders **********************************-->
    <!--******************************* Cancelled Orders **********************************-->
    <?php if (sizeof($cancelled_orders_rows) > 0) { ?>
        <div id="cancelled_orders_switch_container" class="position-relative me-3">
            <div id="cancelled_orders_switch" class="form-check form-switch ms-auto end-0 mt-3 mb-2">
                <label class="form-check-label" for="flexSwitchCheckDefault">Show cancelled orders (<?php echo sizeof($cancelled_orders_rows) ?>)</label>
                <input class="form-check-input" type="checkbox" role="switch" id="show_cancelled_switch" name="show_cancelled_switch" />
            </div>
        </div>
    <?php } ?>
    <div id="cancelled_orders_container" class="invisible order-tabcontent p-4 p-md-5 mx-0 mx-md-4 mt-2">
        <div class="rounded-1 mb-3">
            <?php
            if (sizeof($cancelled_orders_rows) > 0) {
                for ($i = 0; $i < sizeof($cancelled_orders_rows); $i++) {
                    $row = $cancelled_orders_rows[$i];
                    include $vars['row_template_path'];
                    $row = null;
                }
                $i = null;
            }
            ?>
        </div>
    </div>
    <!--******************************* Cancelled Orders **********************************-->
</div>

<!-- *********************Cancellation reason modal start*********************************** -->
<?php
 $max_word_length_for_reason = $obj_config::getvar("MAX_WORD_LENGTH_FOR_REASON");
 ?>
<div class="col-sm-12 col-md-3 col-lg-3">
            <div class="modal fade cancellation_reason_modal" id="cancellation_reason" data-backdrop="static" aria-hidden="true" aria-labelledby="cancellation_reason" tabindex="-2">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <!-- <i class="fas fa-cart-shopping fa-2x" style="margin-right: 15px;"></i> -->
                            <h6 class="modal-title" id="cancellation_reason">Please provide a reason for a cancellation request</h6>
                            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <input type="hidden" id="max_word_length" value="<?php echo $max_word_length_for_reason; ?>" />
                            <textarea id="request_cancellation_reason" rows="4" class="form-control" onkeypress="checkMaxlengthAndRestrict(event,this.value,<?php echo $max_word_length_for_reason; ?>)">

                            </textarea>
                        </div>
                        <div class="modal-footer" style="display: flex;justify-content:center;">
                            <button class="common_btn mt-0" id="cancel_confirm">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- *********************Cancellation reason modal end*********************************** -->


<!-- *********************Show cancellation reason modal start*********************************** -->
<div class="col-sm-12 col-md-3 col-lg-3">
            <div class="modal fade cancellation_reason_modal" id="show_cancellation_reason_popup" data-backdrop="static" aria-hidden="true" aria-labelledby="show_cancellation_reason_popup" tabindex="-2">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <!-- <i class="fas fa-cart-shopping fa-2x" style="margin-right: 15px;"></i> -->
                            <h6 class="modal-title">Reason:</h6>
                            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 285px;height: auto;overflow-y: scroll;">
                            <p id="whole_submitted_reason"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- *********************Show cancellation reason modal end*********************************** -->