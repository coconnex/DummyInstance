<?php

namespace Coconnex\API\ExhibitingCompany\Models\RequestModels;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Base/AbstractClasses/Model.Abstract.php");
require_once(dirname(__FILE__) . "/CompanyRequestModel.Class.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\ExhibitingCompany\Models\RequestModels\CompanyRequestModel;


class ExhibitorRequestModel extends Model
{
    public $nid;
    public $type = 'exhibitor';
    public $field_main_company;
    public $field_sf_accountid;
    public $field_sf_accountnid;
    public $field_ex_company;
    public $field_billing_company;
    public $field_shipping_company;
    public $field_company_logo;
    public $field_company_is_active;
    public $field_sf_lead_nid;
    public $field_industry_sector;
    public $field_industry_subsector;
    public $field_company_type_key;

    public function init()
    {
        $this->field_main_company = new CompanyRequestModel();
        $this->field_billing_company = new CompanyRequestModel();
        $this->field_shipping_company = new CompanyRequestModel();
    }
}
