<?php
global $base_url;
$current_theme = variable_get('theme_default', 'none');
$theme_array = variable_get('theme_' . $current_theme . '_settings', 'none');
$logopath = '/' . $theme_array['logo_path'];
?>
<section class="pt-3">
    <div class="row bg_white container comman_center_position" id="confirm_participation">
        <div class="col-sm-12 col-md-12 col-lg-12 min_height_content px-5 pt-4 pt-md-5 pb-4 registration_confirm_participation " id="registration_confirm_participation">
            <div><a href="<?php echo $base_url; ?>" ><i class="fas fa-arrow-left mt-md-2 ms-md-3"></i></a></div>
            <div class="confirm_participation_logo text-center">
                <img src="<?php echo $logopath; ?>" alt="logo" width="250px" class="img-fluid">
            </div>
            <div class="text-center col-sm-6 registration_confirm_participation-message  mx-auto">
                <h4 class="mb-4 mt-5  text-center mx-auto ">Congratulations! We have been able to successfully register your company for the show.</h4>
                <p>We have sent you an invitation sharing your secure login information</p>
            </div>
        </div>
    </div>
</section>