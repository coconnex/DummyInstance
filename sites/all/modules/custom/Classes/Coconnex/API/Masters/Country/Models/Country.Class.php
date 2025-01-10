<?php

namespace Coconnex\API\Masters\Country\Models;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Base/Models/Country/Models/CountryModel.Class.php");

use Coconnex\API\Base\Models\Country\Models\CountryModel;

class Country extends CountryModel
{
    public $id;
    public $deleted;
}
