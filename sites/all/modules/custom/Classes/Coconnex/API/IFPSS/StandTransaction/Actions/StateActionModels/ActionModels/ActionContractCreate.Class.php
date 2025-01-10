<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractCreate extends StateAction
{
    public $id = 'ID_CNT_CREATE';
    public $name = 'Create Contract';
    public  $key = "CNT_CREATE";
    public  $roles = array('exhibitor', 'organiser');
    public  $state = NULL;

    public function __construct()
    {
        parent::__construct();
    }
}
