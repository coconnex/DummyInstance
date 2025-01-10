<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CONTENT_TYPE_STATUS_ENTITY_MODEL extends Db{

	public $vid;
	public $nid;
	public $field_status_description_value;
	public $field_status_color_value;
	public $field_status_key_value;
	public $field_status_sequence_value;
	public $field_status_is_active_value;
	public $field_font_color_value;
	public $field_svg_pattern_value;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->vid = $id;
        $this->configureMeta($uid,'content_type_status','vid');
        parent::__construct($id);
	}
}