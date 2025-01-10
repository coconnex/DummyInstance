<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions;

require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnecter.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;

class Search extends APIConnector{

    public function __construct()
    {
        parent::__construct('transactions');
    }

}