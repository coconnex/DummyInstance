<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels;

require_once(dirname(__FILE__) . "/ActionModels/ActionCancelReserved.Class.php");
require_once(dirname(__FILE__) . "/ActionModels/ActionContractCreate.Class.php");

class StateReserveActions
{
    public static $actions = array('ActionCancelReserved');
}
