<?php

namespace Coconnex\API\Base\Models\OtherInformation\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/OtherInformation.Trait.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\OtherInformation\Traits\OtherInformation;


class OtherInformationModel extends Model
{
    use OtherInformation;

    public function init()
    {
    }
}
