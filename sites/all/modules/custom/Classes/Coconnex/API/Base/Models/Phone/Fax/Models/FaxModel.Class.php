<?php

namespace Coconnex\API\Base\Models\Phone\Fax\Models;

require_once(dirname(dirname(dirname(__FILE__))) . "/Traits/Phone.Trait.php");
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\Phone\Traits\Phone;

class FaxModel extends Model
{
    use Phone;

    public function init()
    {
    }
}
