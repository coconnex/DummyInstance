<?php
namespace Coconnex\API\IFPSS\Exhibitor\Models\EntityModels;

use Coconnex\DBFactory\Db;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/DBFactory/Db.Class.php");



Class ExhibitorEntityModel extends Db{
    
    public $id;
    public $company_name;
    public $registration_id;
    public $user_ref_id;
    public $external_ref_id;
    public $is_validated;
    public $enabled;
    public function __construct($uid=0,$id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid
                            ,'cnx_exhibitor'
                            ,'id'
                            ,'created_on'
                            ,'created_by'
                            ,'modified_on'
                            ,'modified_by');
        parent::__construct($id);
	}
}






