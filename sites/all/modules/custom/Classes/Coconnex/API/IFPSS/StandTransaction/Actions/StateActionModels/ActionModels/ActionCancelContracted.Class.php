<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionCancelContracted extends StateAction
{
    public $id = 'ID_CNT_CANCEL';
    public $name = 'Contract Cancellation';
    public  $key = "CNT_CANCEL";
    public  $roles = array('organiser');
    public  $state = "CANCELLED";

    public function __construct()
    {
        parent::__construct();
    }
}
