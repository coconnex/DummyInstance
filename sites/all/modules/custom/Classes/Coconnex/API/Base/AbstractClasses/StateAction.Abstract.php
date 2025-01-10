<?php

namespace Coconnex\API\Base\AbstractClasses;

require_once(dirname(dirname(__FILE__)) . "/Models/CallBack.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/Control.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/IFPSS/StandTransaction/Actions/StateActionModels/TransactionActions.Class.php");

use Coconnex\API\Base\Models\CallBack;
use Coconnex\API\Base\Models\Control;
use Coconnex\API\IFPSS\StandTransaction\Actions\StateActionModels\TransactionActions;

abstract class StateAction
{
    public $id;
    public $name;
    public $key;
    public $roles;
    public $state;
    public $callback;
    public $control;

    public function __construct()
    {
        $this->callback = new CallBack();
        $this->control = new Control();
        $this->set_control();
    }

    public function __clone()
    {
        $this->callback = clone $this->callback;
        $this->control = clone $this->control;
    }

    public function set_control($styles = "", $jsfunction = "")
    {
        $this->control->id = $this->id;
        $this->control->name = $this->name;
        $this->control->styles = $styles;
        $this->control->jsfunction = $jsfunction;
        $this->control->link = (!empty($this->callback->url)) ? $this->callback->url : '/action/'.$this->key;
        $this->control->set_html();
    }




}
