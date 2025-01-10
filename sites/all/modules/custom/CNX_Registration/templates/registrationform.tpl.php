<?php

global $base_url;
require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/Utils/Handlers/AssetHandler.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/Utils/Config/Config.Class.php");

use Coconnex\Utils\Config\Config;
use Coconnex\Utils\Handlers\AssetHandler;
use Coconnex\Utils\Extractors\TelephoneExtractor\CountryCodeExtractor;

$current_theme = variable_get('theme_default', 'none');
$theme_array = variable_get('theme_' . $current_theme . '_settings', 'none');
$logopath = '/' . $theme_array['logo_path'];

$fpath = $base_url . "/" . drupal_get_path('module', 'CNX_Registration');
if (isset($vars['exhibitor'])) {
    $exhibitor_data =  $vars['exhibitor'];
    $exhibitor_object = (object)$exhibitor_data;
    $exhibitor_main_comp = (object)$exhibitor_object->field_main_company;
    $address = $exhibitor_main_comp->addresses['data'];
    $address_main = $address['MAIN'];
    $contact_info_telephone = $exhibitor_main_comp->contact_info['landline'];
    if (isset($contact_info_telephone['number'])) {
        $extractor = new CountryCodeExtractor($contact_info_telephone['number']);
        $extractor_result = $extractor->extract_country_code(false, true);
        if (isset($extractor_result['success'])) {
            if ($extractor_result['success']) {
                if (isset($extractor_result['size_is_valid'])) {
                    if ($extractor_result['size_is_valid']) {
                        $contact_info_telephone['country_code'] = $extractor_result['extracted_code'];
                        $contact_info_telephone['number'] = $extractor_result['extracted_remaining'];
                    }
                }
            }
        }
        $extractor = null;
        $extractor_result = null;
    }
    $statutory_information = $exhibitor_main_comp->statutory_information;
    $contact = $exhibitor_main_comp->contacts['data'];
    $contact_main = $contact['MAIN'];
    $crm_contact_nid = $contact_main['crm_reference_nid'];
    $crm_account_nid =  $exhibitor_main_comp->crm_reference_nid;
}

if (isset($vars['sector_list'])) {
    $sector_list = $vars['sector_list'];
}
foreach ($sector_list as $sector) {
    if ($sector['id'] == $exhibitor_object->field_industry_sector) {
        foreach ($sector['children'] as $subsector) {
            // print_r($subsector);
            if ($subsector['id'] == $exhibitor_object->field_industry_subsector) {
                $subsectorname = $subsector['name'];
            }
        }
    }
}
$edit_falg = $vars['checkedit'];
if ($edit_falg) {
    $edit_prop = 'disabled';
} else {
    $edit_prop = '';
}
$country_list = (object)$vars['country_list'];

if (!isset($vars['readonly'])) {
    $vars['readonly'] = false;
}
if (!isset($vars['registration_mode'])) {
    $vars['registration_mode'] = false;
}
if (isset($vars['editsector']) && $vars['editsector'] > 0) {
    $editsector = 'disabled';
} else {
    $editsector = '';
}

$obj_config = new Config("d6");
$reCAPTCHA_secret_key =  $obj_config->getvar('reCAPTCHA_secret_key');
$reCAPTCHA_site_key =  $obj_config->getvar('reCAPTCHA_site_key');

$back_arrow_link = '/user';
$is_crm_integrated = $obj_config::getvar("IS_CRM_INTEGRATED");
if($is_crm_integrated == 1){
    $back_arrow_link = '/participation/confirm';
}

AssetHandler::add_asset("/assetscdn/registration/scripts/registration.js", "script");
?>

<!-- <script src="<?php echo $fpath ?>/assets/scripts/registration.js"></script> -->
<div class="container">
    <form class="form needs-validation" name="frm_register" method="post" id="registration_form" novalidate>
        <!-- <form class="form needs-validation" name="frm_register" method="post" id="registration_form" onsubmit="return validate(this)" > -->
        <div class="registration-form-wrapper" id="registration_content">
            <div class="row">
                <div class="col-xl-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card py-2 px-4 p-md-3 pt-md-0" id="registration_card">
                        <div class="card_body">
                            <div>
                                <?php if ($vars['registration_mode']) { ?>
                                    <a href="<?php echo $base_url . $back_arrow_link; ?>">
                                        <i class="fas fa-arrow-left mt-2  ms-2 mt-md-4 ms-md-3"></i>
                                    </a>
                                <?php } ?>

                            </div>
                            <?php if ($vars['registration_mode'] === true || $vars['compulsory_edit_mode'] === true) { ?>
                                <div class="form-header">
                                    <div class="registration_logo">
                                        <h3 class=""><img id="registration_logo_img" src="<?php echo $logopath; ?>" alt="form logo" class="img-fluid mw-75"></h3>
                                    </div>

                                </div>
                            <?php } ?>
                            <!-- *******************Exhibitor's details code*************************** -->
                            <?php if ($vars['edit_mode']) {
                                $edit_class = 'edit-fieldset-wrapper';
                            } else {
                                $edit_class = '';
                            } ?>
                            <div class="fieldset-wrapper pt-5 p-3 pb-0 <?php echo  $edit_class; ?>" id="fieldset-wrapper">
                                <fieldset class="group-main collapsible">
                                    <legend class="collapse-processed pt-3"><a href="#" id="exhibitor_details">Exhibitor's details</a></legend>
                                    <div class="fieldset-wrapper registration-content" id="exhibitor_details_content">
                                        <?php if (!$vars['readonly']) { ?>
                                            <div class="description">
                                                <p>Please provide the exhibitor details exactly as you wish them to appear in our records and other event listings. Note that the contact entered here should be your main organiser, who will serve as the primary point of contact for the organiser. If you need to make any changes to this information after registration, please reach out to us.</p>
                                            </div>
                                        <?php } ?>
                                        <div class="row ">
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-company-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) {
                                                            if ($crm_account_nid > 0) {
                                                                $disable_comp_name = 'disabled';
                                                                $label_active = 'active';
                                                        ?>
                                                                <input type="hidden" name="company_name" value="<?php echo $exhibitor_object->field_ex_company; ?>">
                                                            <?php
                                                            } else {
                                                                $disable_comp_name = '';
                                                                $label_active = '';
                                                            }
                                                            ?>
                                                            <input id="edit-field-ex-company-0-value" type="text" maxlength="100" name="company_name" size="100" pattern=".{1,100}" value="<?php echo $exhibitor_object->field_ex_company; ?>" class="form-control <?php echo $label_active; ?>" required <?php echo $edit_prop; ?> <?php echo $disable_comp_name; ?>>
                                                            <input type="hidden" name="main_comp_id" value="<?php echo $exhibitor_main_comp->id; ?>" maxlength="150">
                                                            <input type="hidden" name="sf_lead_nid" value="<?php echo $exhibitor_object->field_sf_lead_nid; ?>">
                                                            <input type="hidden" name="sf_account_nid" value="<?php echo  $crm_account_nid; ?>">
                                                            <input type="hidden" name="sf_contact_nid" value="<?php echo  $crm_contact_nid; ?>">
                                                            <input type="hidden" name="company_type_key" value="<?php echo $exhibitor_object->field_company_type_key; ?>">
                                                            <input type="hidden" name="edit_flag" value="<?php echo  $edit_prop; ?>">
                                                            <label for="edit-field-ex-company-0-value" class="form-label">Legal Company Name *</label>
                                                            <div class="invalid-feedback">Provide your legal company name.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">Legal Company Name
                                                                <div>
                                                                    <span><?php echo $exhibitor_object->field_ex_company; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <input type="hidden" name="registration_id" value="<?php echo $vars['registration_id']; ?>">
                                                        <input type="hidden" name="back_end_ref_id" value="<?php echo $vars['backend_end_ref']; ?>">
                                                    </div>
                                                    <?php if (!$vars['readonly']) { ?>
                                                        <div class="description pt-2"></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-address1-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" name="company_address1" id="edit-field-ex-address1-0-value" size="50" value="<?php echo $address_main['line_1']; ?>" class="form-control" maxlength="250" required <?php echo $edit_prop; ?>>
                                                            <input type="hidden" name="main_comp_add_id" value="<?php echo $address_main['id']; ?>">
                                                            <input type="hidden" name="main_type" value="MAIN">
                                                            <label for="edit-field-ex-address1-0-value" class="form-label">Street *</label>
                                                            <div class="invalid-feedback">Provide your mailing street.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">Street
                                                                <div>
                                                                    <span><?php echo  $address_main['line_1']; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-town-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" maxlength="50" name="company_city" id="edit-field-ex-town-0-value" size="50" value="<?php echo $address_main['city']; ?>" class="form-control" required pattern=".{3,50}" <?php echo $edit_prop; ?>>
                                                            <label for="edit-field-ex-town-0-value" class="form-label">City *</label>
                                                            <div class="invalid-feedback">Provide your city.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">City
                                                                <div>
                                                                    <span><?php echo $address_main['city']; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <!-- <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-website-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" maxlength=""  class="form-control" name="company_website" id="edit-field-ex-website-0-value" size="" value="<?php echo $exhibitor_main_comp->website_url; ?>" class="form-control"
                                                            pattern="^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$"
                                                            required >
                                                            <label for="edit-field-ex-website-0-value" class="form-label">Company Website *</label>
                                                            <div class="invalid-feedback">Provide the company website.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">Company Website
                                                                <div>
                                                                    <span><?php  echo $exhibitor_main_comp->website_url; ?> </span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-postcode-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" maxlength="15" name="company_postcode" id="edit-field-ex-postcode-0-value" size="15" value="<?php echo $address_main['postal_code']; ?>" class="form-control" required <?php echo $edit_prop; ?>>
                                                            <label for="edit-field-ex-postcode-0-value" class="form-label">ZIP/Postal Code *</label>
                                                            <div class="invalid-feedback">Provide the postal code.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">ZIP/Postal Code
                                                                <div>
                                                                    <span><?php echo $address_main['postal_code']; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="" id="edit-field-ex-country-id-value-wrapper">
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <select name="company_country_id" class="form-select custom-select" id="edit-field-ex-country-id-value" data-mdb-select-init required data-mdb-filter="true" data-mdb-validation="true" data-mdb-invalid-feedback="Select your address country." data-mdb-valid-feedback=" " <?php echo $edit_prop; ?>>
                                                                <option value=""></option>
                                                                <?php if ($country_list) {
                                                                    foreach ($country_list as $country) {
                                                                        if ($address_main['country']['id'] == $country['id']) {
                                                                            $selected = "selected";
                                                                        } else {
                                                                            $selected = '';
                                                                        }
                                                                ?>
                                                                        <option value="<?php echo $country['id']; ?>" <?php echo $selected; ?>><?php echo $country['country_name'] . " (" . $country['iso_code'] . ")"; ?></option>
                                                                <?php  }
                                                                } ?>
                                                            </select>
                                                            <label for="edit-field-ex-country-id-value" class="form-label select-label">Country *</label>
                                                        <?php } else { ?>
                                                            <div id="" class="">Country
                                                                <div>
                                                                    <span><?php echo $address_main['country']['country_name']; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="" id="edit-field-ex-telephone-countries-id-value-wrapper">
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <select name="country_code" class="form-select browser-default custom-select" id="edit-field-ex-telephone-countries-id-value" data-mdb-select-init data-mdb-filter="true" data-mdb-validation="true" data-mdb-invalid-feedback="Select your telephone country." data-mdb-valid-feedback=" ">
                                                                <option value=""></option>
                                                                <?php if ($country_list) {
                                                                    foreach ($country_list as $country) {
                                                                        if (!empty($contact_info_telephone['country_code'])) {
                                                                            if (trim($contact_info_telephone['country_code']) == trim($country['international_telephone_code'])) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = '';
                                                                            }
                                                                        }

                                                                        $cty_val = $country['international_telephone_code'];
                                                                        if (empty($cty_val)) {
                                                                            $cty_val = $country['iso_code'];
                                                                        }
                                                                        if (empty($cty_val)) {
                                                                            $cty_val = $country['country_name'];
                                                                        }

                                                                ?>
                                                                        <option value="<?php echo $cty_val; ?>" <?php echo $selected; ?>><?php echo $country['country_name'] . " (" . $cty_val . ")"; ?></option>
                                                                <?php  }
                                                                } ?>
                                                            </select>
                                                            <label for="edit-field-ex-telephone-countries-id-value" class="form-label select-label">Telephone:Country *</label>
                                                        <?php } else { ?>
                                                            <div id="" class="">Telephone: Country
                                                                <div>
                                                                    <span><?php echo $contact_info_telephone['country_code']; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-telephone-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="tel" maxlength="15" name="cont_telephone" id="edit-field-ex-telephone-0-value" size="50" value="<?php echo $contact_info_telephone['number']; ?>" class="form-control" required pattern="[0-9\-\s\(\)\+]{9,15}">
                                                            <label for="edit-field-ex-telephone-0-value" class="form-label">Telephone *</label>
                                                            <div class="invalid-feedback">Provide your telephone number.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">Telephone
                                                                <div>
                                                                    <span><?php echo $contact_info_telephone['number'] ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <?php if ($crm_account_nid > 0) { ?>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                                    <div class="md-form">
                                                        <div class="form-outline" id="edit-field-ex-po-number-0-value-wrapper" data-mdb-input-init>

                                                            <?php if (!$vars['readonly']) { ?>
                                                                <input type="text" name="purchase_order" maxlength="50" id="edit-field-ex-po-number-0-value" size="60" value="<?php echo $statutory_information['po_number']; ?>" class="form-control">
                                                                <label for="edit-field-ex-po-number-0-value" class="form-label ">Purchase Order number </label>
                                                            <?php } else { ?>
                                                                <div>Purchase Order number <div>
                                                                        <span><?php echo $statutory_information['po_number']; ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <div class="description"> (if needs to appear on the invoice)
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                                    <div class="md-form">
                                                        <div class="form-outline" id="edit-field-ex-vat-details-0-value-wrapper" data-mdb-input-init>
                                                            <?php if (!$vars['readonly']) { ?>
                                                                <input type="text" name="vat_details" maxlength="50" id="edit-field-ex-vat-details-0-value" size="60" value="<?= ($statutory_information['field_sf_ex_vat_number']) ? $statutory_information['field_sf_ex_vat_number'] : $statutory_information['vat_number']  ?>" class="form-control">
                                                                <label for="edit-field-ex-vat-details-0-value" class="form-label">VAT </label>
                                                            <?php } else { ?>
                                                                <div>VAT<div>
                                                                        <span><?php echo $statutory_information['vat_number']; ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <div class="description"> (if needs to appear on the invoice)
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </fieldset>
                                <fieldset class="group-contact collapsible">
                                    <legend class="collapse-processed pt-3"><a href="#">Main contact</a></legend>
                                    <div class="fieldset-wrapper">
                                        <?php if (!$vars['readonly']) { ?>
                                            <div class="description">
                                                <p><i>Please provide contact details for invoicing purposes. If you need to make any changes to this section after registration, please email us at&nbsp;</i><a data-auth="NotApplicable" data-linkindex="0" data-safelink="true" href="mailto:<?php echo $vars['sales_team_email']; ?>" rel="noopener noreferrer" target="_blank"><?php echo $vars['sales_team_email']; ?></a></p>
                                            </div>
                                        <?php } ?>

                                        <div class="row pt-3 ">
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-contactfirstname-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" maxlength="50" name="ex_contactfirstname" id="edit-field-ex-contactfirstname-0-value" pattern=".{1,50}" size="50" value="<?php echo $contact_main['first_name']; ?>" class="form-control" required>
                                                            <input type="hidden" name="main_comp_cont_id" value="<?php echo $contact_main['id']; ?>">
                                                            <label for="edit-field-ex-contactfirstname-0-value" class="form-label">First Name *</label>
                                                            <div class="invalid-feedback">Provide contact first name.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">
                                                                First Name
                                                                <div><span><?php echo $contact_main['first_name']; ?></span></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-contactsurname-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" maxlength="50" name="ex_contactsurname" id="edit-field-ex-contactsurname-0-value" size="50" value="<?php echo $contact_main['last_name']; ?>" class="form-control" required pattern=".{1,50}">
                                                            <label for="edit-field-ex-contactsurname-0-value" class="form-label">Last Name *</label>
                                                            <div class="invalid-feedback">Provide contact last name.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">
                                                                Last Name
                                                                <div><span><?php echo $contact_main['last_name']; ?></span></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-contactposition-0-value-wrapper"edit-field-ex-contactposition-0-value-wrapper>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="text" maxlength="50" name="ex_contactposition" id="edit-field-ex-contactposition-0-value" size="50" value="<?php echo $contact_main['job_position']; ?>" class="form-control" pattern=".{1,50}" >
                                                            <label for="edit-field-ex-contactposition-0-value" class="form-label">Job Position </label>
                                                        <?php } else { ?>
                                                            <div id="" class="">
                                                                Job Position
                                                                <div><span><?php echo $contact_main['job_position']; ?></span></div>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="" id="edit-field-ex-contactposition-0-value-wrapper" edit-field-ex-contactposition-0-value-wrapper>
                                                        <?php if (!$vars['redaonly']) { ?>
                                                            <select name="ex_contactposition" class="form-select custom-select" id="edit-field-ex-contactposition-0-value" data-mdb-select-init data-mdb-filter="true" data-mdb-valid-feedback=" ">
                                                                <option value="">--None--</option>
                                                                <option value="Administrative" <?php if ($contact_main['job_position'] == 'Administrative') {
                                                                                                    echo 'selected';
                                                                                                }  ?>> Administrative </option>
                                                                <option value="C-Level" <?php if ($contact_main['job_position'] == 'C-Level') {
                                                                                            echo 'selected';
                                                                                        }  ?>> C-Level </option>
                                                                <option value="President / VP / Director" <?php if ($contact_main['job_position'] == 'President / VP / Director') {
                                                                                                                echo 'selected';
                                                                                                            }  ?>> President / VP / Director </option>
                                                                <option value="Head / Manager" <?php if ($contact_main['job_position'] == 'Head / Manager') {
                                                                                                    echo 'selected';
                                                                                                }  ?>>Head / Manager </option>
                                                                <option value="Non-Managerial" <?php if ($contact_main['job_position'] == 'Non-Managerial') {
                                                                                                    echo 'selected';
                                                                                                }  ?>> Non-Managerial </option>
                                                            </select>
                                                            <label for="edit-field-ex-contactposition-0-value" class="form-label select-label">Job Position </label>
                                                        <?php } else { ?>
                                                            <div id="" class="">
                                                                Job Position
                                                                <div><span><?php echo $contact_main['job_position']; ?></span></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row  pb-3">
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-ex-contactemail-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) {
                                                            if ($vars['edit_mode']) {
                                                                $disable_edit_mail = 'disabled';
                                                                $label_active = 'active';
                                                        ?>
                                                                <input type="hidden" value="<?php echo $contact_main['email']; ?>" name="ex_contactemail">
                                                            <?php
                                                            } else {
                                                                $disable_edit_mail = '';
                                                                $label_active = '';
                                                            }  ?>
                                                            <input type="email" maxlength="100" name="ex_contactemail" id="edit-field-ex-contactemail-0-value" size="50" value="<?php echo $contact_main['email']; ?>" class="form-control <?php echo $label_active; ?>" <? echo $disable_edit_mail; ?> required>
                                                            <label for="edit-field-ex-contactemail-0-value" class="form-label">E-mail *</label>
                                                            <div class="invalid-feedback">Provide contact email.</div>
                                                        <?php } else { ?>
                                                            <div id="" class="">
                                                                E-mail
                                                                <div><span><?php echo $contact_main['email']; ?></span></div>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                                <div class="md-form">
                                                    <div class="form-outline" id="edit-field-additional-contact-email-a-0-value-wrapper" data-mdb-input-init>
                                                        <?php if (!$vars['readonly']) { ?>
                                                            <input type="email" name="additional_contact_email" id="edit-field-additional-contact-email-a-0-value" size="60" value="<?php echo $contact_main['additional_email']; ?>" maxlength="150" class="form-control ">
                                                            <label for="edit-field-additional-contact-email-a-0-value" class="form-label">Additional contact email address
                                                            </label>

                                                        <?php } else { ?>
                                                            <div id="" class="">
                                                                Additional contact email address
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </fieldset>
                                <fieldset class="group-sector">
                                    <legend class="collapse-processed pt-3"><a href="#">Industry Sector</a></legend>
                                    <div class="description">
                                        <p><i>Please select your industry sector and subsector. If you need to change this information after registration, please email us at&nbsp;</i><a data-auth="NotApplicable" data-linkindex="0" data-safelink="true" href="mailto:<?php echo $vars['sales_team_email']; ?>" rel="noopener noreferrer" target="_blank"><?php echo $vars['sales_team_email']; ?></a></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <div class="md-form">
                                                <div class="mb-3" id="comapny-sector-value-wrapper">
                                                    <select name="comapny_sector_id" class="form-select custom-select" onchange="getSubSector()" id="comapny-sector-value" data-mdb-select-init required data-mdb-filter="true" data-mdb-validation="true" data-mdb-invalid-feedback="Select your sector." data-mdb-valid-feedback=" " <?php echo $editsector; ?>>
                                                        <?php
                                                        foreach ($sector_list as $sector) {
                                                            if ($sector['count'] > 0) {
                                                                if ($vars['registration_mode'] && $sector['id'] == 1) {
                                                                    $selected = 'selected';
                                                                } elseif ($vars['edit_mode'] == true && $exhibitor_object->field_industry_sector == $sector['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                } ?>
                                                                <option value="<?php echo $sector['id']; ?>" <?php echo $selected ?>><?php echo $sector['name']; ?></option>
                                                        <?php   }
                                                        }
                                                        ?>
                                                    </select>
                                                    <label for="comapny-sector-value" class="form-label select-label">Sector *</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <div class="md-form">
                                                <div class="" id="subsectorForm-wrapper">
                                                    <select name="comapny_sub_sector_id" class="form-select custom-select" id="subsectorForm" data-mdb-select-init required data-mdb-filter="true" data-mdb-validation="true" data-mdb-invalid-feedback="Select your sub sector." data-mdb-valid-feedback=" " <?php echo $editsector; ?>>
                                                        <option value="<?php echo $exhibitor_object->field_industry_subsector ?>" selected> <?php echo  $subsectorname; ?> </option>
                                                    </select>
                                                    <label for="subsectorForm" class="form-label select-label">Sub Sector *</label>
                                                    <input type="hidden" id="subsector_edit" value="<?php echo $exhibitor_object->field_industry_subsector ?>">
                                                    <input type="hidden" id="subsector_edit_name" value="<?php echo $exhibitor_object->field_industry_subsector ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>
                                </fieldset>
                            </div>
                            <input type="hidden" name="recaptcha_site_key" id="recaptcha_site_key" value="<?php echo $reCAPTCHA_site_key; ?>">
                            <input type="hidden" id='registration_mode' value="<?php echo $vars['registration_mode']; ?>">
                            <div class="mt-md-4 row">
                                <div class="d-grid">
                                    <?php if (!$vars['readonly']) {
                                        if ($vars['registration_mode']) {
                                            $value = "Create New Account";
                                        } else {
                                            $value = "Update Profile";
                                        } ?>
                                        <div id="html_element" class="ms-auto me-auto mb-1"></div>
                                        <input type="submit" name="op" id="create-submit" value="<?php echo $value; ?>" class="common_btn ms-auto me-auto w-50">
                                    <?php } ?>
                                </div>
                                <?php if ($vars['registration_mode']) { ?>
                                    <div class="registration_btn mt-md-2 text-center pb-2 pb-md-0">
                                        Already have an account?
                                        <a href="<?php echo $base_url; ?>">
                                            Sign in
                                        </a>
                                    </div>

                                <?php } ?>
                            </div>

                        </div>
                    </div>
                    <!-- *******************Exhibitor's details code*************************** -->
                </div>
            </div>
        </div>
</div>
</form>
<?php
if ($vars['registration_mode']) { ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>
<?php
} ?>

</div>
<script>
    let sector_list = JSON.parse('<?php echo addslashes(json_encode($vars['sector_list'])); ?>');
</script>