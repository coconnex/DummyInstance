<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractCounterSign extends StateAction
{
    public $id = 'ID_CNT_COUNTERSIGN';
    public $name = 'Counter Sign Contract';
    public  $key = "CNT_COUNTERSIGN";
    public  $roles = array('organiser');
    public  $state = "CONTRACT_COMPLETED";

    public function __construct()
    {
        parent::__construct();
    }
}
