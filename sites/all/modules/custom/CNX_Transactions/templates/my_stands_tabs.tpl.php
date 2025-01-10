<?php
require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/Utils/Handlers/AssetHandler.Class.php");

use Coconnex\Utils\Config\Config;
use Coconnex\Utils\Handlers\AssetHandler;

AssetHandler::add_asset("/assetscdn/floorplan/scripts/stand_packages.js", "script");
AssetHandler::add_asset("/assetscdn/transactions/scripts/myStandTabsController.js", "script");
AssetHandler::add_asset("/assetscdn/transactions/scripts/transactionsController.js", "script");
AssetHandler::add_asset("/assetscdn/transactions/scripts/timer.js", "script");
AssetHandler::add_asset("/assetscdn/transactions/styles/booking-timer.css", "stylesheet");

$rows = ($vars['status'] == 'WAITLISTED') ? $vars['waiting_list']->waiting_list : $vars['transactions']->exhib_stand_transactions;
?>
<!-- Tabs navs start-->
<h5 class='common_spaceing heading_color'>My Stands</h5>
<div class="min_height_content">
    <div class="mystand_tab">
        <section class="tabs-wrapper common_spaceing">
            <div class="tabs-container">
                <div class="tab">
                    <button id="tab_0" class="tablinks <?php echo ($vars['status'] == 'RESERVED') ? "active" : ""; ?>" status="reserved">Reserved &nbsp;<span class="tab-row-count"><?php echo ($vars['row_counts']['RESERVED']) ? $vars['row_counts']['RESERVED'] : '0'; ?></span></button>
                    <button id="tab_1" class="tablinks <?php echo ($vars['status'] == 'BOOKED') ? "active" : ""; ?>" status="booked">Booked &nbsp;<span class="tab-row-count"><?php echo ($vars['row_counts']['BOOKED']) ? $vars['row_counts']['BOOKED'] : '0'; ?></span></button>
                    <?php $obj_config = new Config("d6");
                    $is_waitinglist_active = $obj_config::getvar("IS_WAITINGLIST");
                    $is_graceperiod_active = $obj_config::getvar("IS_GRACEPERIOD");
                    $grace_period_config = $obj_config::get_grace_period_config();
                    if($is_waitinglist_active == 1){ ?>
                        <button id="tab_2" class="tablinks <?php echo ($vars['status'] == 'WAITLISTED') ? "active" : ""; ?>" status="waitlisted">Waitlisted &nbsp;<span class="tab-row-count"><?php echo ($vars['row_counts']['WAITLISTED']) ? $vars['row_counts']['WAITLISTED'] : '0'; ?></span></button>
                    <?php } ?>
                    <button id="tab_3" class="tablinks <?php echo ($vars['status'] == 'CANCELLED') ? "active" : ""; ?>" status="cancelled">Cancelled &nbsp;<span class="tab-row-count"><?php echo ($vars['row_counts']['CANCELLED']) ? $vars['row_counts']['CANCELLED'] : '0'; ?></span></button>
                </div>
                <!-- ***************************Tab Content Start************************************-->
                <div id="tab_content" class="tabcontent p-4 p-md-5">
                    <?php if ($vars['status'] == 'RESERVED' && !$vars['is_validated'] && ($vars['row_counts']['RESERVED']) > 0) { ?>
                        <div class="alert" role="alert" data-mdb-color="info" data-mdb-alert-init>
                            <i class="fas fa-chevron-circle-right me-3"></i>Your registration details are being validated. The stands you have reserved will be open for contracting as soon as the validation process is completed. We will keep you informed over email.
                        </div>
                    <?php } ?>
                    <?php if ($vars['status'] == 'RESERVED' && ($vars['row_counts']['RESERVED']) > 0 && $vars['is_validated']) { ?>
                        <form action="/contract/submit" method="post" id="reserved_stand_form">
                        <?php }
                    if (sizeof($rows) > 0) {
                        for ($i = 0; $i < sizeof($rows); $i++) {
                            $row = $rows[$i];
                            // debug($row);
                            include $vars['row_template_path'];
                            $row = null;
                        }
                        $i = null;
                    } else { ?>
                            <br />
                            <div style="text-align: center;">
                                <h3>No records found.</h3>
                            </div>
                        <?php }

                    if ($vars['status'] == 'RESERVED' && ($vars['row_counts']['RESERVED']) > 1 && $vars['is_validated']) { ?>
                            <div id="contract_creation"><input type="button" id="contract_creation_btn" onclick="check_selected_stand_for_contracts()" class="common_btn mt-0" value="Book Now" /></div>
                        </form>
                    <?php }  ?>
                </div>
                <!-- ***************************Tab Content End *********************************-->
            </div>
        </section>
    </div>
</div>
<!-- Tabs navs end-->
<?php
if ($vars['status'] == 'BOOKED') {
    $max_word_length_for_reason = $obj_config::getvar("MAX_WORD_LENGTH_FOR_REASON");
?>
    <!-- *********************Cancellation reason modal start*********************************** -->
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
                        <textarea id="request_cancellation_reason" rows="4" class="form-control" onkeypress="checkMaxlengthAndRestrict(event,this.value,<?php echo $max_word_length_for_reason; ?>)"></textarea>
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

<?php
}
?>

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

