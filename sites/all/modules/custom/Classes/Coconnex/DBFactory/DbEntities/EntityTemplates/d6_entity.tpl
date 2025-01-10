
namespace Coconnex\DBFactory\DbEntities\Applications\%s\D6\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Vendors/Drupal/Dnode/Db.Class.php");

use Coconnex\DBFactory\Vendors\Drupal\Dnode\db;

class %s_ENTITY_MODEL extends db{

%s

    public function __construct($nid = null){
        parent::__construct($nid);
    }
}