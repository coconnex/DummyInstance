<?php
namespace Coconnex\Utils\Config;
include_once(dirname(__FILE__) . "/Vendors/D6/D6.Class.php");

use Coconnex\Utils\Config\Vendors\D6\D6;

class ConfigFactory
{
	public static function getObject($configType){
		$objPM = null;
		switch(strtolower(trim($configType))){
			case 'd6':
				return new D6();
				break;
			// case 'd7':
			// 	$objPM = new D7Db();
			// 	break;
			// case 'mysql':
			// 	$objPM = new MySqlDb();
			// break;
            // case 'config':
			// 	$objPM = new MySqlDb();
			// break;
			default:
				break;
		}
	}

}

?>