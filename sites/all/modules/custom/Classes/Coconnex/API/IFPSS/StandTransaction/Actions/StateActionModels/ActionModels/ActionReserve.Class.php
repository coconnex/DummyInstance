<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionReserve extends StateAction
{
    public $id = 'ID_STAND_RVD';
    public $name = 'Reserve Stand';
    public $key = "STAND_RVD";
    public $roles = array('exhibitor');
    public $state = "RESERVED";

    public function __construct()
    {
        parent::__construct();
    }
}
