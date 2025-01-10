<?php
global $base_url;
require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/Utils/Handlers/AssetHandler.Class.php");

use Coconnex\Utils\Handlers\AssetHandler;

AssetHandler::add_asset("/assetscdn/registration/scripts/emailsearch.js", "script");
$fpath = $base_url . "/" . drupal_get_path('module', 'CNX_Registration');
$current_theme = variable_get('theme_default', 'none');
$theme_array = variable_get('theme_' . $current_theme . '_settings', 'none');
$logopath = '/' . $theme_array['logo_path'];
//echo "<h5 class='common_spaceing heading_color'>Accept Email</h5>";
?>


<!-- <script src="<?php echo $fpath ?>/assets/scripts/emailsearch.js"></script> -->
<section class="pt-3">
    <div class="row bg_white container comman_center_position" id="confirm_participation">
        <div class="col-sm-12 col-md-12 col-lg-12 min_height_content px-5  pt-4 pb-3 p-md-5 pt-md-0 registration_confirm_participation" id="registration_confirm_participation">
        <div><a href="<?php echo $base_url . '/participation/confirm'; ?>"><i class="fas fa-arrow-left"></i></a></div>

            <div class="confirm_participation_logo text-center">
                <img src="<?php echo $logopath; ?>" alt="logo" class="img-fluid">
            </div>
            <div class="confirm_participation_info mt-5">
                <p class="mt-5 mb-3 h4 text-center"><b>Thank you for your repeated participation!</b></p>
                <p class="mb-4 text-center">We will register you automatically by fetching your information using your primary email.</p>
            </div>

            <div class="row confirm_participation_content">
                <div class="col-sm-12 col-md-6 col-lg-6 border_right  ms-3 ms-md-0">
                    <div class="d-none d-md-block confirm_participation ">
                        <div>
                            <ol>
                                <li>Provide your main email address.
                                <li>We will look it up in our records.
                                <li>If we find it, we will verify it using an email code.
                                <li>Once verified, we will show your company's legal name.
                                <li>Finally, we will confirm your automated registration.
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6  padding_left">
                    <div class="row mb-3 ">
                        <div class="form-outline  primary-email search_div mb-2" id="search_div" data-mdb-input-init>
                            <input type="text" class="form-control disable_input" name="primary_email" id="primary_email" value="" required />
                            <label class="form-label disable_input" for="primary_email">Primary email</label>
                        </div>
                        <div class="col-md-2 mt-2 mt-md-0 p-0 ms-md-3 d-flex  justify-content-md-start justify-content-end">
                            <button type="button" class="btn btn-primary" id="search_result" style="width: max-content;" onclick="searchEmail()" data-mdb-ripple-init>Search</button>
                        </div>
                    </div>
                    <!-- Search div -->
                    <div class="row" id="search_result_div">
                        <div class="d-flex p-0">
                            <p class="p-0 m-0">Please wait searching for your information.</p>
                            <div class="spinner-border loader_spaceing" role="status">
                                <span class="visually-hidden"> Loading...</span>
                            </div>
                        </div>
                    </div>
                    <!-- Found Match Div -->
                    <div class="row text-center me-1" id="otp_result_div">
                        <p class="p-0 pb-3 mt-3">We found a match!</p>
                        <div class="col-sm-12 col-lg-8 detabase_contact_email ms-0 mx-md-auto">
                            <div id="comp_name"></div><br />
                            <div id="vat_no"></div>
                        </div>
                        <div class="pt-3 pb-2">
                            <p class="my-0">To reveal the company name and continue,</p>
                            <p class="my-0">please enter the OTP we have emailed to</p>
                            <p class="my-1" id="cust_email"></p>
                        </div>
                        <div class="password_content d-lg-flex justify-content-center p-0">
                            <div class="password_box mt-3 mb-2 w-auto" id="otp_inputs" style="width: fit-content;">
                                <input type="text" class="password_box_input" id="otp1" maxlength="1" oninput="moveToNextOrPrevious(this, 'otp2', 'otp4')" value="" required>
                                <input type="text" class="password_box_input" id="otp2" maxlength="1" oninput="moveToNextOrPrevious(this, 'otp3', 'otp1')" value="" required>
                                <input type="text" class="password_box_input" id="otp3" maxlength="1" oninput="moveToNextOrPrevious(this, 'otp4', 'otp2')" value="" required>
                                <input type="text" class="password_box_input" id="otp4" maxlength="1" oninput="moveToNextOrPrevious(this, 'otp1', 'otp3')" value="" required>
                            </div>
                            <div id="timer_div" class="d-flex-column mt-3 mt-md-0 mb-2 justify-content-right align-items-center mx-auto mx-md-0">
                                <div class="text-success display_none" id="resend_text">OTP expires in</div>
                                <div id="countdown-element" class="p-0 justify-content-center align-items-center" data-mdb-countdown-text-style="badge bg-dark" style="width: fit-content;">
                                    <div class="countdown-unit countdown-minutes"></div>
                                    <div class="countdown-unit countdown-seconds"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-3 mb-4 mb-md-2">
                                <button type="button" class="btn btn-primary display_none justify-content-center align-items-center" id="resend_btn" style="width: max-content;" onclick="generateOTP()" data-mdb-ripple-init>Resend OTP</button>
                            </div>
                        </div>
                        <div class="row " id="otp_error_div">
                            <div class="d-flex p-0 justify-content-center align-items-center ">
                                <p class="p-0 m-0 justify-content-center align-items-center text-danger" id="otp_error_message"></p>
                            </div>
                        </div>
                    </div>
                    <!-- Show company div -->
                    <div class="display_none" id="company_name_div">
                        <div class="company_vat_no box_shadow p-2 p-md-3  mb-3  mx-auto rounded-2">
                            <p id="company_name_value"></p>
                            <p id="vat_no_value"></p>
                        </div>
                        <div class="row pt-3">
                            <div class="col-sm-12 col-md-12 col-lg-12 participation_info_closebtn d-flex justify-content-center align-items-center">
                                <div>
                                    <input class="form-check-input otp_check" type="checkbox" id="checkboxNoLabel2" value="" aria-label="..." />
                                </div>
                                <p>Please verify the company details above and confirm if this company’s information can be used to complete the registration automatically.<i class="text-danger">(If left unchecked, you will proceed to fill out the registration form)</i></p>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 pt-4 pb-3">
                                <button id="registration_now_btn" type="button" class="btn btn-primary w-50 justify-content-center align-items-center registration_now_btn" data-mdb-ripple-init style="width: max-content;" onclick="rgisterExhibitor()">Register Now</button>
                            </div>

                        </div>
                    </div>
                    <div class="row " id="error_div">
                        <div class="d-flex p-0 justify-content-center align-items-center mt-3">
                            <p class="p-0 m-0 justify-content-center align-items-center text-danger" id="error_message"></p>
                        </div>
                    </div>
                    <div class="mt-3 text-center d-none" id="create_accbtn">
                        <a class="btn btn-primary " id="navigation_btn" href="<?php echo $base_url . '/registration/exhibitor'; ?>">Register Now
                        </a>
                    </div>
                    <div class="mt-3 text-center d-none" >
                        <a class="btn btn-primary " id="sign_in" href="<?php echo $base_url; ?>">Sign In Now
                        </a>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
</section>