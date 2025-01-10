<?php

namespace Coconnex\Utils\Handlers;

class GenericHandler{

    public static function get_http_query($data,$doRFC1738 = false)
    {
        return ($doRFC1738) ? http_build_query($data) : self::http_build_query_from_array($data);
    }

    public static function http_build_query_from_array($data)
    {
        $q = array();
        foreach ($data as $key => $value) {
            $q[] = implode('=', array($key, $value));
        }
        return implode('&', $q);
    }

    public static function render_template($template, $vars){
		ob_start();
		foreach ( $vars as $key => $value) {
			${$key} = $value;
		}
		include $template;
		return ob_get_clean();
	}
    public static function obfuscate($variable){
		$str = '';
		if(strlen($variable) > 6){
			for($i= 0; $i<strlen($variable); $i++){
				if($i<2 ||  $i >(strlen($variable)-3)){
					$str .=$variable[$i];
				}else{
					$str .="*";		
			    }
				
			}
			return $str;
		}else{
			for($i= 0; $i<strlen($variable); $i++){
				if($i<1 ||  $i >(strlen($variable)-2)){
					$str .=$variable[$i];
				}else{
					$str .="*";
				}
			}
			return $str;
		}
	}
	public static function getRandomString($length = 6) {
		$characters = '0123456789abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ';
		$string = '';
		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $string;
	}
	public static function getRandomNumber($length = 6) {
		$characters = '0123456789';
		$string = '';
		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $string;
	}

}