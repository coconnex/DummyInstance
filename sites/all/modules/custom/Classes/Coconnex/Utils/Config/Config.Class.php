<?php
namespace Coconnex\Utils\Config;

include_once(dirname(__FILE__) . "/ConfigFactory.Class.php");

use Coconnex\Utils\Config\ConfigFactory;

class Config{

    public static $configObj;

    public function __construct($config_type)
    {
        self::$configObj = ConfigFactory::getObject($config_type);
    }

    public static function getvar($var_name)
    {
        static $temp;
        $temp = self::$configObj;
        return $temp::getvar($var_name);
    }
    public static function getResultset($sql){
        static $temp;
        $temp = self::$configObj;
        return $temp::getResultset($sql);
    }
    public static function getRow($resultSet,$returntype='object'){
		static $temp;
        $temp = self::$configObj;
        return $temp::getRow($resultSet,$returntype='object');
	}
    public static function getLogoPath($themename = 'theme_default'){
        static $temp;
        $temp = self::$configObj;
        return $temp::getLogoPath($themename);
    }
    public static function baseurl(){
        static $temp;
        $temp = self::$configObj;
        return $temp::baseurl();
    }

    public static function uid(){
        static $temp;
        $temp = self::$configObj;
        return $temp::uid();
    }
    public static function getSpecificRoles($role){
        static $temp;
        $temp = self::$configObj;
        return $temp::getSpecificRoles($role);
    }
    public static function getCountryById($id){
        static $temp;
        $temp = self::$configObj;
        return $temp::getCountryById($id);
    }
    public static function string_cleanup($string){
        static $temp;
        $temp = self::$configObj;
        return $temp::string_cleanup($string);
    }

    public static function string_wrap($string,$transaction_id){
        static $temp;
        $temp = self::$configObj;
        return $temp::string_wrap($string,$transaction_id);
    }

    public static function get_grace_period_config(){
        static $temp;
        $temp = self::$configObj;
        return $temp::get_grace_period_config();
    }

    public static function get_grace_period_notify_schedule($gp_config_id){
        static $temp;
        $temp = self::$configObj;
        return $temp::get_grace_period_notify_schedule($gp_config_id);
    }


}