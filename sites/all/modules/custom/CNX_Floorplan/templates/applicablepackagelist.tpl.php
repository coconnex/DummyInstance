<?php use Coconnex\Utils\Config\Config; ?>

<div class="packages">
    <?php

    $obj_config = new Config("d6");
    $sales_team_email = $obj_config::getvar("ORG_SALES_TEAM_EMAIL");

    if ($vars['applicable_package']) {
        $package_list = (object)$vars['applicable_package'];
        // debug($package_list,1);
        foreach ($package_list as $package) {
    ?>
            <div class="packages-details mb-3">
                <?php
                if ($package['description']) { ?>
                    <div class="package-name fw-bold pb-1"> <?php echo $package['description']; ?></div>
                <?php }
                if ($package['summary']) { ?>
                    <div class="package-info"><?php echo $package['summary']; ?></div>
                <?php }

                if ($package['details_link']) { ?>
                    <span class="text-primary view-details" onclick="packageInfo.show('<?php echo $package['details_link']; ?>','<?php echo $package['description']; ?>' )">View Details</span>
                <?php } ?>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="packages-details mb-2">Packages not allocated</div>
        <div id="primeproduct">Please contact sales team
            <span class="d-block"> <a href="mailto:<?php echo $sales_team_email; ?>"><?php echo $sales_team_email; ?></a>
            </span>
        </div>
    <?php
    }
    ?>
</div>
