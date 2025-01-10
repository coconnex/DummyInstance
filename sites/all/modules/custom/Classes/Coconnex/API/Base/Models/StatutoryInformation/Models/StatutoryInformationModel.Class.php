<?php

namespace Coconnex\API\Base\Models\StatutoryInformation\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/StatutoryInformation.Trait.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\StatutoryInformation\Traits\StatutoryInformation;



class StatutoryInformationModel extends Model
{
    use StatutoryInformation;
    public $is_tax_exempt;
    public $tax_exempt_details;
    public $vat_number;
    public $po_number;

    public function init()
    {
    }
}
