<?php

namespace Coconnex\API\Base\Models\Country\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/Country.Trait.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Currency/Models/CurrencyModel.Class.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\Country\Traits\Country;
use Coconnex\API\Base\Models\Currency\Models\CurrencyModel;

class CountryModel extends Model
{
    use Country;

    public function init()
    {
        $this->currency = new CurrencyModel();
    }
}
