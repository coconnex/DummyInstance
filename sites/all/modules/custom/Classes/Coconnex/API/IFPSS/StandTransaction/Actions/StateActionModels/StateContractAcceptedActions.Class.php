<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels;

require_once(dirname(__FILE__) . "/ActionModels/ActionContractCounterSign.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionContractRequestCancellation.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionApproveRequestCancellation.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionDownloadContract.Class.php");

class StateContractAcceptedActions
{
    public static $actions = array('ActionContractCounterSign', 'ActionDownloadContract', 'ActionContractRequestCancellation', 'ActionApproveRequestCancellation');
}
