<?php
namespace Coconnex\API\Reminders\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/DBFactory/Db.Class.php");
use Coconnex\DBFactory\Db;


class Reminder extends Db
{
    public $id;
    public $type;
    public $config;
    public $class_name;
    public $is_active;

    public function __construct($uid, $id = null){
        if(is_numeric($id)) $this->id = $id;
        $this->configureMeta($uid
                            ,'cnx_reminders'
                            ,'id'
                            ,'created_on'
                            ,'created_by'
                            ,'modified_on'
                            ,'modified_by');
        parent::__construct($id);
	}
}