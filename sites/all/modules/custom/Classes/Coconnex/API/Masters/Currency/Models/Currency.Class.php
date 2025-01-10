<?php

namespace Coconnex\API\Masters\Currency\Models;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Base/Models/Currency/Models/CurrencyModel.Class.php");

use Coconnex\API\Base\Models\Currency\Models\CurrencyModel;

class Currency extends CurrencyModel
{
    public $id;
    public $deleted;
}
