<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers;

require_once(dirname(__FILE__) . "/APIManager.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Managers\APIManager;

class LookupManager extends APIManager
{
    protected $stand_number;

    public function __construct($stand_number)
    {
        parent::__construct();
        if($stand_number != ""){
            $this->stand_number = $stand_number;
            $this->api_action = "lookup";
            $this->get_endpoints();
        }
    }


}