<?php

namespace Coconnex\API\ExhibitingCompany\Models;

use Coconnex\API\Base\Models\Contact\Models\ContactModel;

require_once(dirname(dirname(dirname(__FILE__))) . "/Base/Models/Contact/Models/ContactModel.Class.php");

class CompanyContactModel extends ContactModel
{
    public $id;
    public $company_id;
    public $type;
    public $is_active;
    public $deleted;
    public $crm_reference_nid;
}
