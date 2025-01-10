<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractRequestCancellation extends StateAction
{
    public $id = 'ID_CNT_REQ_CANCEL';
    public $name = 'Request Cancellation';
    public  $key = "CNT_REQ_CANCEL";
    public  $roles = array('exhibitor');
    public  $state = "CANCELLATION_REQUESTED";

    public function __construct()
    {
        parent::__construct();
    }
}
