<?php

namespace Coconnex\API\Base\Models\Email\Models;

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\OtherInformation\Traits\Email;

require_once(dirname(dirname(__FILE__)) . "/Traits/Email.Trait.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");


class EmailModel extends Model
{
    use Email;
    public function init()
    {
    }
}
