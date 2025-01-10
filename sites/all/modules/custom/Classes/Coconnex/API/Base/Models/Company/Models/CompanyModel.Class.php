<?php

namespace Coconnex\API\Base\Models\Company\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/Company.Trait.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Contact/Collection/Contacts.Collection.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Address/Collection/Addresses.Collection.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/ContactInformation/Models/ContactInfoModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/OtherInformation/Models/OtherInformationModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/StatutoryInformation/Models/StatutoryInformationModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BankingDetails/Models/BankingDetailsModel.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Email/Models/EmailModel.Class.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\Company\Traits\Company;
use Coconnex\API\Base\Models\BankingDetails\Models\BankingDetailsModel;
use Coconnex\API\Base\Models\Address\Collection\Addresses;
use Coconnex\API\Base\Models\Contact\Collection\Contacts;
use Coconnex\API\Base\Models\ContactInformation\Models\ContactInfoModel;
use Coconnex\API\Base\Models\Email\Models\EmailModel;
use Coconnex\API\Base\Models\OtherInformation\Models\OtherInformationModel;
use Coconnex\API\Base\Models\StatutoryInformation\Models\StatutoryInformationModel;

class CompanyModel extends Model
{
    use Company;
    public $email;
    public $contacts;
    public $addresses;
    public $contact_info;
    public $other_information;
    public $statutory_information;
    public $banking_details;
    public $social_info_json;

    public function init()
    {
        $this->email = new EmailModel();
        $this->contacts = new Contacts();
        $this->addresses = new Addresses();
        $this->contact_info = new ContactInfoModel();
        $this->other_information = new OtherInformationModel();
        $this->statutory_information = new StatutoryInformationModel();
        $this->banking_details = new BankingDetailsModel();
    }
}
