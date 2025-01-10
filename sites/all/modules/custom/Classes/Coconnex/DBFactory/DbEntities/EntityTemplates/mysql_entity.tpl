
namespace Coconnex\DBFactory\DbEntities\Applications\%s\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Vendors\MySql\Db;

class %s_ENTITY_MODEL extends Db{

%s
    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->%s = $id;
        $this->configureMeta(%s);
        parent::__construct($id);
	}
}