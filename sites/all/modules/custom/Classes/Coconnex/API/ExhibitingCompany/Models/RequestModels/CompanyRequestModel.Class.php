<?php

namespace Coconnex\API\ExhibitingCompany\Models\RequestModels;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Base/Models/Company/Models/CompanyModel.Class.php");

use Coconnex\API\Base\Models\Company\Models\CompanyModel;

class CompanyRequestModel extends CompanyModel
{
    public $id;
    public $crm_reference_nid;
    public $deleted;
}
