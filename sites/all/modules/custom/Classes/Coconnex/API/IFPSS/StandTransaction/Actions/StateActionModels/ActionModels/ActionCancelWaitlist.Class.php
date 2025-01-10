<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionCancelWaitlist extends StateAction
{
    public $id = 'ID_WAITLIST_CANCEL';
    public $name = 'Remove From Waitlist';
    public $key = "WAITLIST_CANCEL";
    public $roles = array('exhibitor', 'organiser');
    public $state = "CANCELLED";

    public function __construct()
    {
        parent::__construct();
    }
}
