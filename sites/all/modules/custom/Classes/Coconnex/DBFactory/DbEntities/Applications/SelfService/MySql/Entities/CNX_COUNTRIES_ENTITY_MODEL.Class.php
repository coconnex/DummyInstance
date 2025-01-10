<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_COUNTRIES_ENTITY_MODEL extends Db{

	public $id;
	public $name;
	public $country_name;
	public $iso_code;
	public $region;
	public $active;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_countries','id');
        parent::__construct($id);
	}
}