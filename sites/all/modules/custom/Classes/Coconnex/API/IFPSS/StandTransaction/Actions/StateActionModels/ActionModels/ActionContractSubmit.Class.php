<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractSubmit extends StateAction
{
    public $id = 'ID_CNT_SUBMIT';
    public $name = 'Contract Submit';
    public  $key = "CNT_SUBMIT";
    public  $roles = array('exhibitor', 'organiser');
    public  $state = "CONTRACT_SUBMITTED";

    public function __construct()
    {
        parent::__construct();
    }
}
