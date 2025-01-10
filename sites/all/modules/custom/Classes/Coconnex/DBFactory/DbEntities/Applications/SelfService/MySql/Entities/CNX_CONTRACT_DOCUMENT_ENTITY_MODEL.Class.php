<?php

namespace Coconnex\DBFactory\DbEntities\Applications\MySql\Entities;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Db;

Class CNX_CONTRACT_DOCUMENT_ENTITY_MODEL extends Db{

	public $id;
	public $contract_id;
	public $doc_id;
	public $deleted;

    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid,'cnx_contract_document','id','created_on','created_by','modified_on','modified_by');
        parent::__construct($id);
	}
}