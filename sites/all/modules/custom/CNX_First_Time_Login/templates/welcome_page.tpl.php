<!-- <h1>This is welocome page</h1> -->


<div class="welcome-page">
    <div class="row m-0">
        <div class="col-lg-4 col-md-12 welcome-page-hero-img">
            <!-- <img src="/sites/all/modules/custom/CNX_UIDevelopment/welcome_page/templates/welcome_page_image.png" class="col-img d-none d-lg-block"> -->
            <!-- <img src="/sites/all/modules/custom/CNX_UIDevelopment/welcome_page/templates/welcome_page_img_mobile.png" class="col-img-2 d-lg-none d-block"> -->
        </div>
        <div class="col-lg-8 col-md-12 information-sec">
            <div class="header">
                <h5 class="m-0 pt-3 pt-lg-0">Welcome to <?php echo $vars['site_name']; ?> self service stand booking opportunity!</h5>
            </div>
            <div class="description">
                <div class="company-name">This opportunity applies to <strong><?php echo $vars['company_name']; ?></strong></div>
                <div class="info">
                    <p class="m-0">
                        On behalf of the entire <?php echo $vars['ops_team_name']; ?>, I'd like to thank you for your continued support. We're working hard to ensure we deliver an enjoyable and profitable event for all our exhibitors and we wanted to take this opportunity to wish you every success onsite at the show.
                    </p>
                </div>
            </div>
            <div class="instructions-steps">
                <div class="instruction">
                    We understand you're busy, so we've made it quick and easy for you to book your stand in <strong> 5 easy steps:</strong>
                </div>
                <div class="steps">

                    <div class="table-section">
                        <div class="d-flex m-0 justify-content-start">
                            <div class=" table-col-first text-white text-center pt-3">
                                1.
                            </div>
                            <div class="w-100 table-col-second bg-grey-striped p-3">
                                <h2 class="second-col-h2">EXPLORE FLOORPLAN</h2>
                                <p>Navigate to the floor plan by clicking on the <span class="badge badge-first badge-blue">Continue to Book Your Stand</span> appearing below.</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            <div class="table-col-first text-white text-center pt-3">
                                2.
                            </div>
                            <div class="w-100 table-col-second p-3">
                                <h2 class="second-col-h2">Select stand AND Add to cart</h2>
                                <p>Please select an <span class="badge badge-yellow rounded-pill text-black round-peel">AVAILABLE</span> stand, select a preferred package and add to cart.</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            <div class="table-col-first text-white text-center pt-3">
                                3.
                            </div>
                            <div class="w-100 table-col-second bg-grey-striped p-3">
                                <h2 class="second-col-h2">Reserve STAND</h2>
                                <p>Reserve Stand and arrive on the MY STANDS screen. Your reservation will be visible on the reserved tab. Your Stand will appear as <span class="badge badge-blue rounded-pill text-black round-peel">RESERVED </span> on the floor plan.</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            <div class="table-col-first text-white text-center pt-3">
                                4.
                            </div>
                            <div class="w-100 table-col-second p-3">
                                <h2 class="second-col-h2">Initiate Stand Booking</h2>
                                <p>Click on the <span class="badge badge-black bg-black text-white">CREATE CONTRACT</span> button appearing against your reservation. You will arrive on the contract preview screen. Review the contract, sign and submit it. Please note that your stand will still be shown as <span class="badge badge-blue rounded-pill text-black round-peel">RESERVED BY YOU</span> on the floor plan.</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            <div class="table-col-first text-white text-center pt-3">
                                5.
                            </div>
                            <div class="w-100 table-col-second bg-grey-striped p-3">
                                <h2 class="second-col-h2">RECEIVE Booking confirmation</h2>
                                <p>Our team will be notified of the action and we will review the contract and counter sign it. Your stand is now. booked and will now appear in the <span class="badge badge-pink rounded-pill text-black round-peel">BOOKED</span> tab on the MY STANDS screen. The floor plan will also show that the stand is <span class="badge badge-pink rounded-pill text-black round-peel">BOOKED BY YOU</span> </p>
                            </div>
                        </div>

                        <div class="text-center pt-4">
                            <a href="<?php echo $base_url . "/floorplan"; ?>">
                                <button class="badge-blue rounded-2 text-white book-stand">Continue to Book Your Stand</button>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>