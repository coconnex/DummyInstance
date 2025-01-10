<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels;

require_once(dirname(__FILE__) . "/ActionModels/ActionContractSign.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionContractRequestCancellation.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionApproveRequestCancellation.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionContractSignInvitation.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionContractSignReminder.Class.php");

class StateContractSubmittedActions
{
    public static $actions = array('ActionContractSign', 'ActionContractSignReminder', 'ActionContractRequestCancellation', 'ActionApproveRequestCancellation');
}
