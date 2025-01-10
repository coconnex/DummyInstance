<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/API/IFPSS/StandTransaction/Actions/StandTransactionActionController.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/API/IFPSS/StandTransaction/Actions/StateActionModels/TransactionActions.Class.php");

use Coconnex\API\IFPSS\StandTransaction\Actions\StandTransactionActionController;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\TransactionActions;

$transaction = new TransactionActions();
$transaction_action_controller = new StandTransactionActionController();


$transaction::$ActionCancelReserved->callback->class = 'StandTransactionActionController';
$transaction::$ActionCancelReserved->callback->function = 'cancel_reservation';
$transaction::$ActionCancelReserved->callback->url = "/mystands/action/" . $transaction::$ActionCancelReserved->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionCancelReserved->set_control($styles,$jsfunction);

$transaction::$ActionReserve->callback->url = "mystands/action/" . $transaction::$ActionReserve->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionReserve->set_control($styles,$jsfunction);

$transaction::$ActionApproveRequestCancellation->callback->url = "mystands/action/" . $transaction::$ActionApproveRequestCancellation->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionApproveRequestCancellation->set_control($styles,$jsfunction);

$transaction::$ActionCancelContracted->callback->url = "mystands/action/" . $transaction::$ActionCancelContracted->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionCancelContracted->set_control($styles,$jsfunction);

$transaction::$ActionContractCounterSign->callback->url = "mystands/action/" . $transaction::$ActionContractCounterSign->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionContractCounterSign->set_control($styles,$jsfunction);

$transaction::$ActionContractCreate->callback->url = "mystands/action/" . $transaction::$ActionContractCreate->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionContractCreate->set_control($styles,$jsfunction);

$transaction::$ActionContractRequestCancellation->callback->url = "/mystands/action/" . $transaction::$ActionContractRequestCancellation->key;
$styles = 'common_btn';
$jsfunction = 'request_cancellation';
$transaction::$ActionContractRequestCancellation->set_control($styles,$jsfunction);

$transaction::$ActionContractSign->callback->url = "/mystands/action/" . $transaction::$ActionContractSign->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionContractSign->set_control($styles,$jsfunction);

$transaction::$ActionContractSignInvitation->callback->url = "mystands/action/" . $transaction::$ActionContractSignInvitation->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionContractSignInvitation->set_control($styles,$jsfunction);

$transaction::$ActionContractSignReminder->callback->url = "mystands/action/" . $transaction::$ActionContractSignReminder->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionContractSignReminder->set_control($styles,$jsfunction);

$transaction::$ActionContractSubmit->callback->url = "mystands/action/" . $transaction::$ActionContractSubmit->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionContractSubmit->set_control($styles,$jsfunction);

$transaction::$ActionDownloadContract->callback->url = "/mystands/action/" . $transaction::$ActionDownloadContract->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionDownloadContract->set_control($styles,$jsfunction);

$transaction::$ActionCancelWaitlist->callback->url = "/mystands/action/" . $transaction::$ActionCancelWaitlist->key;
$styles = 'common_btn';
$jsfunction = 'manage_actions';
$transaction::$ActionCancelWaitlist->set_control($styles,$jsfunction);

