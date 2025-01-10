<?php

namespace Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels;

require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionCancelReserved.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionReserve.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionApproveRequestCancellation.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionCancelContracted.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractCounterSign.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractCreate.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractRequestCancellation.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractSign.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractSignInvitation.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractSignReminder.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionContractSubmit.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionDownloadContract.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Actions/StateActionModels/ActionModels/ActionCancelWaitlist.Class.php");

use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionApproveRequestCancellation;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionCancelContracted;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionCancelReserved;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionCancelWaitlist;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractCounterSign;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractCreate;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractRequestCancellation;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractSign;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractSignInvitation;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractSignReminder;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionContractSubmit;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionReserve;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\ActionDownloadContract;

Class TransactionActions{

    public static $ActionCancelReserved;
    public static $ActionReserve;
    public static $ActionApproveRequestCancellation;
    public static $ActionCancelContracted;
    public static $ActionContractCounterSign;
    public static $ActionContractCreate;
    public static $ActionContractRequestCancellation;
    public static $ActionContractSign;
    public static $ActionContractSignInvitation;
    public static $ActionContractSignReminder;
    public static $ActionContractSubmit;
    public static $ActionDownloadContract;
    public static $ActionCancelWaitlist;

    public function __construct()
    {
        self::$ActionCancelReserved = new ActionCancelReserved();
        self::$ActionReserve = new ActionReserve();
        self::$ActionApproveRequestCancellation = new ActionApproveRequestCancellation();
        self::$ActionCancelContracted = new ActionCancelContracted();
        self::$ActionContractCounterSign = new ActionContractCounterSign();
        self::$ActionContractCreate = new ActionContractCreate();
        self::$ActionContractRequestCancellation = new ActionContractRequestCancellation();
        self::$ActionContractSign = new ActionContractSign();
        self::$ActionContractSignInvitation = new ActionContractSignInvitation();
        self::$ActionContractSignReminder = new ActionContractSignReminder();
        self::$ActionContractSubmit = new ActionContractSubmit();
        self::$ActionDownloadContract = new ActionDownloadContract();
        self::$ActionCancelWaitlist = new ActionCancelWaitlist();
    }
}