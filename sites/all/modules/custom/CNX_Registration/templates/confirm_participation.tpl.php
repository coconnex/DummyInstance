<?php
global $base_url;
$current_theme = variable_get('theme_default', 'none');
$theme_array = variable_get('theme_' . $current_theme . '_settings', 'none');
$logopath = '/' . $theme_array['logo_path'];
?>
<section class="pt-3">
    <div class="row bg_white container comman_center_position" id="confirm_participation">
        <div><a href="<?php echo $base_url; ?>" ><i class="fas fa-arrow-left mt-4 ms-2 mt-md-5 ms-md-3"></i></a></div>
        <?php

        ?>
        <div class="col-sm-12 col-md-12 col-lg-12 min_height_content text-center p-5 pt-2  pt-md-3 registration_confirm_participation" id="registration_confirm_participation">
            <div class="confirm_participation_logo mb-5">
                <img src="<?php echo $logopath; ?>" alt="logo" width="250px" class="img-fluid">
            </div>
            <p class="mt-5 mb-3"><b>Have you exhibited before with Clarion Events</b></p>
            <div class="row confirm_participation_content mx-auto" id="confirm_participation_content">
                <div class="col-sm-12 col-md-6">
                    <div class="confirm_participation_first text-center row ">
                        <div class="col-4 col-md-2 my-auto">
                            <a href="<?php echo $base_url . '/registration/check'; ?>" class="btn btn-primary mt-4 mt-md-3 mb-3 email_btn">Yes</a>
                        </div>
                        <div class="col-8 col-md-10">
                            <p class="text-left">
                                We will use information from previous shows to automatically register you.
                            </p>
                        </div>


                    </div>
                </div>

                <div class="col-sm-12 col-md-6 mt-4 mt-md-0">
                    <div class="confirm_participation_second row">
                        <div class="col-4 col-md-2">
                            <a href="<?php echo $base_url . '/registration/exhibitor'; ?>" class="btn btn-primary mt-4 mt-md-3 mb-3 email_btn">No</a>
                        </div>
                        <div class="col-8 col-md-10">
                            <p class="text-left">You will need to fill out the registration form manually</p>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

