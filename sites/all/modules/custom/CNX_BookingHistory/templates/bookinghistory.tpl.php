<?php
echo "<h5 class='common_spaceing heading_color'>Booking History</h5>";
// echo '<pre>';
// print_r($vars);
// exit;

if (isset($vars['historyList']['selectedValue']['selected_typeFilter'])) {

    $selectedTypeFilter = $vars['historyList']['selectedValue']['selected_typeFilter'];
}
if (isset($vars['historyList']['selectedValue']['selected_txtDescriptionFilter'])) {
    $selected_txtDescriptionFilter = $vars['historyList']['selectedValue']['selected_txtDescriptionFilter'];
}
if (isset($vars['historyList']['selectedValue']['selected_txtDateFilterFrom'])) {
    $selected_txtDateFilterFrom = $vars['historyList']['selectedValue']['selected_txtDateFilterFrom'];
}
if (isset($vars['historyList']['selectedValue']['selected_txtDateFilterTo'])) {
    $selected_txtDateFilterTo = $vars['historyList']['selectedValue']['selected_txtDateFilterTo'];
}
if (isset($vars['historyList']['selectedValue']['selected_txtUserFilter'])) {
    $selected_txtUserFilter = $vars['historyList']['selectedValue']['selected_txtUserFilter'];
}


?>
<script type="text/javascript">
    function search(a) {
        var frm = document.frmSearch;
        if (a == 'Reset') {
            document.getElementById("frmSearch").reset();
            document.getElementById("typeFilter").value = "";
            document.getElementById("txtDateFilterFrom").value = "";
            document.getElementById("txtDateFilterTo").value = "";
            document.getElementById("txtDescriptionFilter").value = "";
            document.getElementById("txtUserFilter").value = "";


            frm.submit();
            //  return true;
        } else {
            frm.submit();
        }
        return true;
    }
</script>
<form name="frmSearch" id="frmSearch" method="post">
    <div class="common_spaceing_booking booking_histroy">
        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3 booking_form_spaceing">
                <div class="booking_action">

                    <select data-mdb-select-init name="typeFilter" id="typeFilter">
                        <option value="">All</option>
                        <?php if (count($vars['optionList']) > 0) {
                            foreach ($vars['optionList'] as $option) { ?>
                                <option value="<?= $option ?>" <?= ($selectedTypeFilter == $option) ? 'selected' : '' ?>><?php echo str_replace('_', ' ', $option) ?></option>
                        <?php }
                        } ?>
                    </select>


                    <label class="form-label select-label">Status</label>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 booking_form_spaceing">
                <div class="date_picker">
                    <div class="form-outline" data-mdb-input-init data-mdb-inline="true">
                        <input type="date" class="form-control" datetime="<?php echo date('d/m/Y'); ?>" name="txtDateFilterFrom" id="txtDateFilterFrom" value="<? echo $selected_txtDateFilterFrom; ?>" />
                        <label for="txtDateFilterFrom" class="form-label">Select from date</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 booking_form_spaceing">
                <div class="date_picker">
                    <div class="form-outline" data-mdb-input-init data-mdb-inline="true">
                        <input type="date" class="form-control" datetime="<?php echo date('d/m/Y'); ?>" name="txtDateFilterTo" id="txtDateFilterTo" value="<? echo $selected_txtDateFilterTo; ?>" />
                        <label for="txtDateFilterTo" class="form-label">Select to date</label>
                    </div>
                </div>

            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 booking_form_spaceing">
                <div class="form-outline" data-mdb-input-init>
                    <input type="text" name="txtDescriptionFilter" id="txtDescriptionFilter" value="<? echo $selected_txtDescriptionFilter; ?>" class="form-control" />
                    <label class="form-label" for="txtDescriptionFilter">Description</label>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 booking_form_spaceing">
                <div class="form-outline" data-mdb-input-init>
                    <input type="text" name="txtUserFilter" id="txtUserFilter" value="<? echo $selected_txtUserFilter; ?>" class="form-control" />
                    <label class="form-label" for="txtUserFilter">User</label>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 mt-2 mb-2 booking_form_spaceing">
                <button class="common_btn me-3" type="button" name="btnSearchFilter" value="Apply" alt="Apply" title="Apply" onclick="return search(this.value);">Apply</button>
                <button class="common_btn" type="button" name="btnSearchReset" value="Reset" alt="Reset" title="Reset" onclick="return search(this.value);">Reset</button>
            </div>
        </div>
        <!-- *********************booking history card**************************-->
    </div>
</form>
<div class="booking_histroy">
    <!-- ***************card start***************** -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date (<?php echo date_format(new DateTime('now'), 'e'); ?>)</th>
                    <th>Message</th>
                    <th>Action</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //  echo '<pre>';print_r($vars['historyList']['selectedValue']);exit;

                unset($vars['historyList']['selectedValue']);
                if ($vars['historyList']) {
                    foreach ($vars['historyList'] as $row) {
                        // debug($row);
                        //  unset($row['selectedValue']);

                        $action = ucwords(strtolower(str_replace('_', ' ', $row['action'])));
                        $description = $row['description'];
                        $createdDate = $row['created_date'];
                        $user = ($row['user']) ? $row['user'] : "";
                        $user_name = $row['modified_by_name'];
                ?>
                        <tr>
                            <td><?php echo date_format(new DateTime($createdDate), 'd-M-Y H:i:s'); ?></td>
                            <td><?php echo trim($description); ?></td>
                            <td><?php echo strtoupper(trim($action)); ?></td>
                            <!-- <td><?php //echo strtoupper(trim($user)); ?></td> -->
                            <td><?php echo strtoupper($user_name); ?></td>

                        </tr>
                    <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="4">No records found</td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
    <!-- ***************card end***************** -->

</div>
<!-- *********************booking history card**************************-->