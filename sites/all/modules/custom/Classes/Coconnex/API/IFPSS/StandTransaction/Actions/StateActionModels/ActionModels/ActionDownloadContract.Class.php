<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Base/AbstractClasses/StateAction.Abstract.php");

use Coconnex\API\Base\AbstractClasses\StateAction;

class ActionDownloadContract extends StateAction
{
    public $id = 'ID_CNT_PDF_DNWD';
    public $name = 'Download Contract';
    public $key = "CNT_PDF_DNWD";
    public $roles = array('exhibitor', 'organiser');
    public $state = "";

    public function __construct()
    {
        parent::__construct();
    }
}