<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class ROLE_ENTITY_MODEL extends Db{

	public $rid;
	public $name;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->rid = $id;
        $this->configureMeta($uid,'role','rid');
        parent::__construct($id);
	}
}