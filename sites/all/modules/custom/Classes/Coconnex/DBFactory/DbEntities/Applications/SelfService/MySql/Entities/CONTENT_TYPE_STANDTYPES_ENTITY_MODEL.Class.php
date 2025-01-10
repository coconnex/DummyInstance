<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CONTENT_TYPE_STANDTYPES_ENTITY_MODEL extends Db{

	public $vid;
	public $nid;
	public $field_standtype_description_value;
	public $field_standtype_key_value;
	public $field_standtype_is_active_value;
	public $field_standtype_color_value;
	public $field_openside_treatment_value;
	public $field_backend_key_value;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->vid = $id;
        $this->configureMeta($uid,'content_type_standtypes','vid');
        parent::__construct($id);
	}
}