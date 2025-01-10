<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\Collection;

use Coconnex\DBFactory\Db;
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/DBFactory/Db.Class.php");

class StandTypes extends Db{

    protected $arr_stand_types;

    public function __construct(){
        $this->arr_stand_types = array();
        $this->initialise();
    }

    protected function initialise(){
        $str_query = "SELECT nid, field_standtype_description_value description, field_standtype_key_value type_key, field_standtype_color_value type_color,field_backend_key_value backend_key FROM content_type_standtypes WHERE field_standtype_is_active_value=1;";
        $rs = $this->getResultset($str_query);
        while($row = \mysql_fetch_object($rs)){
            $this->arr_stand_types[$row->backend_key] = $row;
		}

        return;
    }

    public function get(){
        return $this->arr_stand_types;
    }
}