<?php

namespace Coconnex\API\ExhibitingCompany\Models;

use Coconnex\API\Base\Models\Address\Models\AddressModel;


require_once(dirname(dirname(dirname(__FILE__))) . "/Base/Models/Address/Models/AddressModel.Class.php");



class CompanyAddressModel extends AddressModel
{
    public $id;
    public $company_id;
    public $type;
    public $is_active;
    public $deleted;
}
