<?php

namespace Coconnex\API\Base\Models\Currency\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/Currency.Trait.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\Currency\Traits\Currency;

class CurrencyModel extends Model
{
    use Currency;

    public function init()
    {
    }
}
