<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractSignReminder extends StateAction
{
    public $id = 'ID_CNT_SIGN_REMINDER';
    public $name = 'Send Signing Reminder';
    public  $key = "CNT_SIGN_REMINDER";
    public  $roles = array('organiser');
    public  $state = NULL;

    public function __construct()
    {
        parent::__construct();
    }
}
