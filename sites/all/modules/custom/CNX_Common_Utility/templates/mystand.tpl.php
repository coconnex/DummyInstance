<?php
echo "<h5 class='common_spaceing heading_color'>My stands</h5>";
?>
<div class="">
    <!-- **************tab content start******************** -->
    <!-- Tabs navs -->
    <div class="min_height_content">
        <div class="mystand_tab">
        <h5 class='common_spaceing heading_color'>My Stands</h5>
            <section class="tabs-wrapper common_spaceing">
                <div class="tabs-container">
                    <div class="tab">
                        <button class="tablinks" onclick="mystand(event, 'Reserved')" id="defaultOpen">Reserved &nbsp;<span>2</span></button>
                        <button class="tablinks" onclick="mystand(event, 'Booked')">Booked &nbsp;<span>2</span></button>
                        <button class="tablinks" onclick="mystand(event, 'Waiting')">Waiting &nbsp;<span>2</span></button>
                        <button class="tablinks" onclick="mystand(event, 'Cancelled')">Cancelled &nbsp;<span>2</span></button>
                    </div>
                    <!-- ***************************Reserved start************************************-->
                    <div id="Reserved" class="tabcontent p-5">
                        <!-- <div class="row-container">
                            <div class="card_one reserve_bg">
                                <div class="contract_deta">
                                    <div class="contract_deta_left w-50">
                                        <span class="heading">
                                            <h5><input type="checkbox" id="vehicle1" name="" value=""><span class="checkmark"></span><strong>&nbsp;&nbsp; 1-H100</strong></h5>
                                        </span>
                                        <div>
                                            <p class="mb-0"> Build Height Limits: 6m</p>
                                            <p>Open Sides: 3</p>
                                            <div class="bottom_fixed">
                                                <p class="contract_submited">Reserved</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contract_deta_right w-50">
                                        <span class="mb-0 heading">
                                            <h5><strong>24 M<sup>2</sup></strong></h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card_two d-flex">
                                <div class="middle_content_left w-50">
                                    <select name="shell_scheme" id="shell_scheme" form="shell_scheme" class="dropdown_space">
                                        <option value="shell">shell</option>
                                        <option value="bare">Bare</option>
                                    </select>
                                    <p class="mystand_card_value mt-3">​Rate £600 / M2</p>
                                </div>
                                <div class="verticaly_border"></div>
                                <div class="middle_content_right w-50">
                                    <p class="mystand_card_value"> Price (Ex.VAT)​</p>
                                    <p class="mystand_card_value"><strong>£14,400 </strong>​</p>
                                </div>
                                <div class="verticaly_border"></div>
                            </div>
                            <div class="card_three">
                                <div class="right_section_btn">
                                    <button class="reserved_Cancel_btn">Cancel</button>
                                </div>
                                <div class="top_label">
                                    <span><img src="/sites/all/themes/exhibitor_zone/images/clock.png" alt="clock" width="30px;"> &nbsp;Expiring On 08-Feb-2023 14:40</span>
                                </div>
                            </div>
                        </div> -->
                        <div class="card ">
                            <div class="row">
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 reserve_bg border-radius">
                                    <div class="contract_deta">
                                        <div class="contract_deta_left w-50">
                                            <span class="heading">
                                                <h5><input type="checkbox" id="vehicle1" name="" value=""><span class="checkmark"></span><strong>&nbsp;&nbsp; 1-H100</strong></h5>
                                            </span>
                                            <div>
                                                <p class="mb-0"> Build Height Limits: 6m</p>
                                                <p>Open Sides: 3</p>
                                                <div class="bottom_fixed">
                                                    <p class="contract_submited">Reserved</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="contract_deta_right w-50">
                                            <span class="mb-0 heading">
                                                <h5><strong>24 M<sup>2</sup></strong></h5>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3  border-end">
                                    <div class="mt-5 text-center mx-auto">
                                        <select name="shell_scheme" id="shell_scheme" form="shell_scheme" class="dropdown_space">
                                            <option value="shell">shell</option>
                                            <option value="bare">Bare</option>
                                        </select>
                                        <p class="mystand_card_value mt-3">​Rate £600 / M2</p>
                                    </div>

                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3  border-end">
                                    <div class="middle_content_right w-50 mt-5 text-center mx-auto">
                                        <p class="mystand_card_value">Price (Ex.VAT)​</p>
                                        <p class="mystand_card_value"><strong>£14,400 </strong>​</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                                    <div class="top_label">
                                        <button type="button" class="btn btn-primary " data-mdb-ripple-init>
                                            <span class=""><img src="/sites/all/themes/exhibitor_zone/images/clock.png" alt="clock" width="30px;"></span>&nbsp;
                                            <span>Expiring On 08-Feb-2023 14:40</span>
                                        </button>
                                    </div>

                                    <div class="right_section_btn">
                                        <button class="reserved_Cancel_btn">Cancel</button>
                                    </div>
                                    <!-- <div class="top_label">
                                        <span><img src="/sites/all/themes/exhibitor_zone/images/clock.png" alt="clock" width="30px;"> &nbsp;Expiring On 08-Feb-2023 14:40</span>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                        <!-- *********************************Reserved card end******************************** -->
                        <!-- *********************************Booked card start******************************** -->
                        <div id="Booked" class="tabcontent">
                            <!-- <div class="row-container">
                                <div class="card_one booked_bg">
                                    <div class="contract_deta">
                                        <div class="contract_deta_left w-50">
                                            <span class="heading">
                                                <h5><strong>1-H100</strong></h5>
                                            </span>
                                            <div>
                                                <p class="mb-0 mt-0"> Build Height Limits: 6m </p>
                                                <p class="mb-0 mt-0">Open Sides: 3</p>
                                                <div class="bottom_fixed">
                                                    <p class="contract_submited_common">Contract Submitted*</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="contract_deta_right w-50">
                                            <span class="mb-0 heading">
                                                <h5><strong>24 M<sup>2</sup></strong></h5>
                                            </span>
                                            <span class="lable">*Shown as reserved on floor plan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card_two d-flex">
                                    <div class="middle_content_left w-50">
                                        <p class="mystand_card_value">Shell Scheme</p>
                                        <p class="mystand_card_value">​Rate £600 / M2</p>
                                    </div>
                                    <div class="verticaly_border"></div>
                                    <div class="middle_content_right w-50">
                                        <p class="mystand_card_value"> Price (Ex.VAT)​</p>
                                        <p class="mystand_card_value"><strong>£14,400</strong>​</p>
                                    </div>
                                    <div class="verticaly_border"></div>
                                </div>
                                <div class="card_three">
                                    <div class="right_section_btn">
                                        <button class="contract_btn">Download Contract
                                            WEB-TD-3002</button><br>
                                        <button class="Cancel_btn">Request Cancellation</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row-container">
                                <div class="card_one booked_bg">
                                    <div class="contract_deta">
                                        <div class="contract_deta_left w-50">
                                            <span class="heading">
                                                <h5><strong>1-H100</strong></h5>
                                            </span>
                                            <div>
                                                <p class="mb-0 mt-0"> Build Height Limits: 6m </p>
                                                <p class="mb-0 mt-0">Open Sides: 3</p>
                                                <div class="bottom_fixed">
                                                    <p class="contract_submited_common">Contract Submitted*</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="contract_deta_right w-50">
                                            <span class="mb-0 heading">
                                                <h5><strong>24 M<sup>2</sup></strong></h5>
                                            </span>
                                            <span class="lable">*Shown as reserved on floor plan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card_two d-flex">
                                    <div class="middle_content_left w-50">
                                        <p class="mystand_card_value">Shell Scheme</p>
                                        <p class="mystand_card_value">​Rate £600 / M2</p>
                                    </div>
                                    <div class="verticaly_border"></div>
                                    <div class="middle_content_right w-50">
                                        <p class="mystand_card_value"> Price (Ex.VAT)​</p>
                                        <p class="mystand_card_value"><strong>£14,400</strong>​</p>
                                    </div>
                                    <div class="verticaly_border"></div>
                                </div>
                                <div class="card_three">
                                    <div class="right_section_btn">
                                        <button class="contract_btn">Download Contract
                                            WEB-TD-3002</button><br>
                                        <button class="Cancel_btn">Request Cancellation</button>
                                    </div>

                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!-- *********************************Booked card end******************************** -->
                    <!-- *******************************Waiting card start******************************* -->
                    <div id="Waiting" class="tabcontent">
                        <div class="row-container">
                            <div class="card_one waiting_bg">
                                <div class="contract_deta">
                                    <div class="contract_deta_left w-50">
                                        <span class="heading">
                                            <h5><strong>1-H100</strong></h5>
                                        </span>
                                        <div>
                                            <p class="mb-0"> Build Height Limits: 6m</p>
                                            <p class="mb-0">Open Sides: 3</p>
                                            <div class="bottom_fixed">
                                                <p class="contract_submited_common">Contract Submitted*</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="contract_deta_right w-50">
                                        <span class="mb-0 heading">
                                            <h5><strong>24 M<sup>2</sup></strong></h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card_two d-flex">
                                <div class="middle_content_left w-50">
                                    <p class="mystand_card_value">Shell Scheme</p>
                                    <p class="mystand_card_value">​Rate £600 / M2</p>
                                </div>
                                <div class="verticaly_border"></div>
                                <div class="middle_content_right w-50">
                                    <p class="mystand_card_value"> Price (Ex.VAT)​</p>
                                    <p class="mystand_card_value"><strong>£14,400</strong>​</p>
                                </div>
                                <div class="verticaly_border"></div>
                            </div>
                            <div class="card_three">
                                <div class="right_section_btn">
                                    <span class="waitlisted_text">Waitlisted at <span class="circle_badge"> 3 </span></span><br>
                                    <button class="Cancel_btn">Remove From Waitlist</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- *******************************Waiting card start******************************* -->
                    <!-- **********************cancelled card start*************************** -->
                    <div id="Cancelled" class="tabcontent">
                        <div class="row-container">
                            <div class="card_one waiting_bg">
                                <div class="contract_deta">
                                    <div class="contract_deta_left w-50">
                                        <span class="heading">
                                            <h5><strong><del>1-H100</del></strong></h5>
                                        </span>
                                        <div>
                                            <p class="mb-0"><del>Build Height Limits: 6m </del></p>
                                            <p class="mb-0"><del>Open Sides: 3</del></p>
                                            <div class="bottom_fixed">
                                                <p class="contract_submited_common">Cancelled​</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="contract_deta_right w-50">
                                        <span class="mb-0 heading">
                                            <h5><strong><del>24 M<sup><del>2</del></sup></del></strong></h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card_two d-flex">
                                <div class="middle_content_left w-50">
                                    <p class="mystand_card_value"><del>Shell Scheme</del></p>
                                    <p class="mystand_card_value"><del>​Rate £600 / M2</del></p>
                                </div>
                                <div class="verticaly_border"></div>
                                <div class="middle_content_right w-50">
                                    <p class="mystand_card_value"><del> Price (Ex.VAT)</del>​</p>
                                    <p class="mystand_card_value"><strong><del>£14,400</del></strong>​</p>
                                </div>
                                <div class="verticaly_border"></div>
                            </div>

                            <div class="card_three">
                                <div class="right_section_btn">
                                    <span class="waitlisted_text">Cancelled on 01-Feb 2024 by</span><br>
                                    <span class="waitlisted_text">Fredrick Jonathan</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- **********************cancelled card end*************************** -->
            </section>
        </div>
    </div>

    <!-- ***************************Footer start***************************** -->
    <footer class="footer_color text-muted footer_all_space">
        <section>
            <div class="common_spaceing mt-5">
                <div class="row mt-3 all_side-space">
                    <div class="col-md-12 col-lg-4 col-xl-4 col-sm-12 col-xs-12 text-white">
                        <div>
                            <h4 class="mt-4 mb-4">VENUE</h4>
                        </div>
                        <p><iframe allowfullscreen="" height="450" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2953.863649967487!2d-0.21503152456150784!3d51.496824497117096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760f8dc9c0b2dd%3A0x26fa091340f02f99!2sOlympia%20London!5e0!3m2!1sen!2suk!4v1692263720556!5m2!1sen!2suk" style="border:0;" width="270"></iframe><br />
                            &nbsp;</p>
                    </div>
                    <div class="col-md-12 col-lg-4 col-xl-4 col-sm-12 col-xs-12 text-white">
                        <div>
                            <h4 class="mt-4 mb-4">
                                OPENING TIMES</h4>
                        </div>
                        <p>Sun 8 September 9:30am - 5.30pm</p>
                        <p>Mon 9 September 9:30am - 5.30pm</p>
                        <p>Tue 10 September 9.30am - 5.00pm</p>
                    </div>
                    <div class="col-md-12 col-lg-4 col-xl-4 col-sm-12 col-xs-12 text-white quick_links_content">
                        <div>
                            <h4 class="mt-4 mb-4">
                                QUICK LINKS</h4>
                        </div>
                        <p><a href="#" class="text-white" alt="faq"><u>FAQ</u></a></p>
                        <p><a href="#" class="text-white" alt="mailing"><u>Sign up to our mailing list</u></a></p>
                        <p><a href="#" class="text-white" alt="Stand enquiry"><u>Stand enquiry</u></a></p>
                        <p><a href="#" class="text-white" alt="suplier"><u>Fraudulent suppliers</u></a></p>
                        <p><a href="#" class="text-white" alt="contact"><u>Contact us</u></a></p>
                        <p><a href="#" class="text-white" alt="code"><u>Code of Conduct</u></a></p>
                    </div>
                </div>
            </div>
        </section>
        <div class="common_spaceing footer_social_content border-top pt-4 pb-4 d-flex icons">
            <div>
                <img alt="hyue_logo" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/clarion.png" width="100px" />
            </div>
            <div>
                <p class="text-white">FIND US ON</p>
                <div class="icons d-flex">
                    <div class="footer-social-icon">
                        <a href="#"><img alt="instagram" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/instagram.png" width="20px" /></a>
                    </div>
                    <div class="footer-social-icon">
                        <a href="#"><img alt="printrest" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/printrest.png" width="20px" /></a>
                    </div>
                    <div class="footer-social-icon">
                        <a href="#"><img alt="facebook" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/facebook.png" width="20px" /></a>
                    </div>
                    <div class="footer-social-icon">
                        <a href="#"><img alt="linkdin" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/linkdin.png" width="20px" /></a>
                    </div>
                    <div class="footer-social-icon">
                        <a href="#"><img alt="twitter_logo" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/twitter_white.png" width="20px" /></a>
                    </div>
                    <div class="footer-social-icon">
                        <a href="#"><img alt="youtub" class="footer_logo" src="/sites/all/themes/exhibitor_zone/images/youtub.png" width="20px" /></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ***************************Footer end***************************** -->

    <!-- ***********************my stand tab code start**************************** -->
    <script>
        function mystand(evt, my_stand) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(my_stand).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("defaultOpen").click();
    </script>
    <!-- ***********************my stand tab code end**************************** -->