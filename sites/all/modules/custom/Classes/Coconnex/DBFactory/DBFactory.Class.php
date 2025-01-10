<?php
namespace Coconnex\DBFactory;
include_once(dirname(__FILE__) . "/Vendors/MySql/Db.Class.php");
include_once(dirname(__FILE__) . "/Vendors/Drupal/D6/Db.Class.php");
include_once(dirname(__FILE__) . "/Vendors/Drupal/D7/Db.Class.php");
include_once(dirname(__FILE__) . "/Vendors/Drupal/Dnode/Db.Class.php");
include_once(dirname(__FILE__) . "/Interfaces/I_DB_Operation.Interface.php");

use Coconnex\DBFactory\Interfaces\I_DB_Operation;
use Coconnex\DBFactory\Vendors\Drupal\D6\Db as D6Db;
use Coconnex\DBFactory\Vendors\Drupal\D7\Db as D7Db;
use Coconnex\DBFactory\Vendors\Drupal\Dnode\Db as DnodeDb;
use Coconnex\DBFactory\Vendors\MySql\Db as MySqlDb;

class DBFactory
{
	public static function getObject($dbType){
		$tempPM = function(I_DB_Operation $dbType)
		{
			return $dbType;
		};
		$objPM = null;
		switch($dbType){
			case 'd6':
				$objPM = new D6Db();
				break;
			case 'd7':
				$objPM = new D7Db();
				break;
			case 'dnode':
				$objPM = new DnodeDb();
				break;
			case 'mysql':
				$objPM = new MySqlDb();
			break;
			default:
				break;
		}
		return $tempPM($objPM);
	}

}

?>