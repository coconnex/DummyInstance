<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class USERS_ENTITY_MODEL extends Db{

	public $uid;
	public $name;
	public $pass;
	public $mail;
	public $mode;
	public $sort;
	public $threshold;
	public $theme;
	public $signature;
	public $signature_format;
	public $created;
	public $access;
	public $login;
	public $status;
	public $timezone;
	public $language;
	public $picture;
	public $init;
	public $data;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->uid = $id;
        $this->configureMeta($uid,'users','uid');
        parent::__construct($id);
	}
}