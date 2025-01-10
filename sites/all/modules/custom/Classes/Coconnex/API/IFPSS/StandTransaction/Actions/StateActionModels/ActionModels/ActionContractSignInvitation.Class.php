<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionContractSignInvitation extends StateAction
{
    public $id = 'ID_CNT_SIGN_INVITE';
    public $name = 'Send Signing Invitation';
    public  $key = "CNT_SIGN_INVITE";
    public  $roles = array('exhibitor', 'organiser');
    public  $state = NULL;

    public function __construct()
    {
        parent::__construct();
    }
}
