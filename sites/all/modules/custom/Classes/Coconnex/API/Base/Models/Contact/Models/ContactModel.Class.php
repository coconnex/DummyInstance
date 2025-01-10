<?php

namespace Coconnex\API\Base\Models\Contact\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/Contact.Trait.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Address/Models/AddressModel.Class.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Phone/Landline/Models/LandlineModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Phone/Mobile/Models/MobileModel.Class.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\Address\Models\AddressModel;
use Coconnex\API\Base\Models\Contact\Traits\Contact;
use Coconnex\API\Base\Models\Phone\Landline\Models\LandlineModel;
use Coconnex\API\Base\Models\Phone\Mobile\Models\MobileModel;

class ContactModel extends Model
{
    use Contact;
    public function init()
    {
        $this->address = new AddressModel();
        $this->telephone = new LandlineModel();
        $this->mobile = new MobileModel();
    }
}
