<?php
namespace Coconnex\API\IFPSS\StandTransaction\Actions\Managers;

require_once(dirname(dirname(__FILE__)) . "/StateActionModels/StateAvailableActions.Class.php");
require_once(dirname(dirname(__FILE__)) . "/StateActionModels/StateReserveActions.Class.php");
require_once(dirname(dirname(__FILE__)) . "/StateActionModels/StateContractSubmittedActions.Class.php");
require_once(dirname(dirname(__FILE__)) . "/StateActionModels/StateContractAcceptedActions.Class.php");
require_once(dirname(dirname(__FILE__)) . "/StateActionModels/StateContractCompletedActions.Class.php");
require_once(dirname(dirname(__FILE__)) . "/StateActionModels/StateWaitlistedActions.Class.php");
require_once(dirname(dirname(__FILE__)) . "/StateActionModels/TransactionActions.Class.php");

use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\StateAvailableActions;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\StateReserveActions;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\StateContractSubmittedActions;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\StateContractAcceptedActions;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\StateContractCompletedActions;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\StateWaitlistedActions;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\TransactionActions;

class StandTransactionActionsManager
{
    protected $transaction_id;
    protected $state_key;
    protected $roles;
    protected $actions;
    protected $action_namespace = "Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\ActionModels\\";

    public function __construct($transaction_id, $roles = array(), $state_key = 'AVAILABLE')
    {
        $this->transaction_id = $transaction_id;
        if ($state_key) $this->state_key = strtoupper(trim($state_key));
        if (is_array($roles)) $this->roles = $roles;
        $this->set_actions();
    }

    protected function set_actions()
    {
        switch ($this->state_key) {
            case "AVAILABLE":
                $this->add_actions(StateAvailableActions::$actions);
                break;
            case "RESERVED":
                $this->add_actions(StateReserveActions::$actions);
                break;
            case "CONTRACT_SUBMITTED":
                $this->add_actions(StateContractSubmittedActions::$actions);
                break;
            case "CONTRACT_ACCEPTED":
                $this->add_actions(StateContractAcceptedActions::$actions);
                break;
            case "CONTRACT_COMPLETED":
                $this->add_actions(StateContractCompletedActions::$actions);
                break;
            case "WAITLISTED":
                $this->add_actions(StateWaitlistedActions::$actions);
                break;

        }
    }

    protected function add_actions($actions)
    {
        for ($i = 0; $i < sizeof($actions); $i++) {
            $action_class = $actions[$i];
            // debug($action_class,1);
            $action = clone TransactionActions::${$action_class};
            $action->control->value = $this->transaction_id;
            $action->control->set_html();

            if ($this->has_permission($action)) {
                $this->actions[] = $action;
            }
        }
    }

    protected function has_permission($action)
    {
        if (count(array_intersect($this->roles, $action->roles))) {
            return true;
        }
        return false;
    }

    public function get_actions()
    {
        return $this->actions;
    }


}
