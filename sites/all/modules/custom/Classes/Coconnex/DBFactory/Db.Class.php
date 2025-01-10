<?php
namespace Coconnex\DBFactory;

include_once(dirname(__FILE__) . "/Vendors/MySql/Db.Class.php");

use Coconnex\DBFactory\Vendors\MySql\Db as MySqlDb;

class Db extends MySqlDb{

    public function __construct($id = null)
    {   
        parent::__construct($id);
    }
}