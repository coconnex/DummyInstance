<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractSign extends StateAction
{
    public $id = 'ID_CNT_SIGN';
    public $name = 'Sign Contract';
    public  $key = "CNT_SIGN";
    public  $roles = array('exhibitor');
    public  $state = "CONTRACT_ACCEPTED";

    public function __construct()
    {
        parent::__construct();
    }
}
