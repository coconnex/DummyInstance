<?php
namespace Coconnex\API\IFPSS\Registration\Models\EntityModels;

use Coconnex\DBFactory\Db;

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/DBFactory/Db.Class.php");



Class RegistrationEntityModel extends Db{
    public $id;
    public $type = 'EXHIBITOR';
    public $method;
    public $data;
    public $backend_ref;
    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid
                            ,'registration_data'
                            ,'id'
                            ,'created_on'
                            ,'created_by'
                            ,'modified_on'
                            ,'modified_by');
        parent::__construct($id);
	}
}