<?php

namespace Coconnex\API\Base\Models\ContactInformation\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/ContactInfo.Trait.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Phone/Landline/Models/LandlineModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Phone/Mobile/Models/MobileModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Phone/Fax/Models/FaxModel.Class.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\ContactInformation\Traits\ContactInfo;
use Coconnex\API\Base\Models\Phone\Landline\Models\LandlineModel;
use Coconnex\API\Base\Models\Phone\Mobile\Models\MobileModel;
use Coconnex\API\Base\Models\Phone\Fax\Models\FaxModel;

class ContactInfoModel extends Model
{
    use ContactInfo;

    public function init()
    {
        $this->landline = new LandlineModel();
        $this->mobile = new MobileModel();
        $this->fax = new FaxModel();
    }
}
