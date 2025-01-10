<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_EMAIL_TYPE_ENTITY_MODEL extends Db{

	public $id;
	public $description;
	public $typekey;
	public $subject;
	public $templatepath;
	public $filename;
	public $additionalrecipients;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_email_type','id');
        parent::__construct($id);
	}
}