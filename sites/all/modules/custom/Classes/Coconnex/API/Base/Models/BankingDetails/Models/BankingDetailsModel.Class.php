<?php

namespace Coconnex\API\Base\Models\BankingDetails\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/BankingDetails.Trait.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\BankingDetails\Traits\BankingDetails;

class BankingDetailsModel extends Model
{
   use BankingDetails;

   public function init()
   {
   }
}
