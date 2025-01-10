<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_EXHIBITOR_ENTITY_MODEL extends Db{

	public $id;
	public $company_name;
	public $user_ref_id;
	public $registration_id;
	public $external_ref_id;
	public $is_validated;
	public $enabled;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_exhibitor','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}