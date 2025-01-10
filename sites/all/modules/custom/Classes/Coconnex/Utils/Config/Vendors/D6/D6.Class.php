<?php
namespace Coconnex\Utils\Config\Vendors\D6;

// require_once(dirname(dirname(__FILE__))."/Utility/Logs.Class.php");
//require_once("Email.Class.php");
class D6{


	public static function user() {
		global $user;
		return $user;
	}

	public static function uid() {
		global $user;
		return $user->uid;
	}

	public static function baseurl(){
		// try{
			global $base_url;
			return $base_url;
		// } catch(Exception $e){
		// 	$tokens = array('{Error Message}' => $e->getMessage());
   		// 	Logs::logException('EXCEPTION','EXP',$tokens);
		// }

	}

	public static function getAppVar($key,$default=''){
		$query = self::getResultset("select variable_value from tbl_app_variables where variable_name = '".$key."' ORDER by id DESC limit 0,1");
		$data = self::getRow($query);
		if(sizeof($data)>0){
			return $data->variable_value;
		} else {
			return $default;
		}
	}

	public static function getvar($key, $default='') {
		// try{
			$temp = variable_get($key, $default);
			return $temp;
		// } catch(Exception $e){
		// 	$tokens = array('{Error Message}' => $e->getMessage());
   		// 	Logs::logException('EXCEPTION','EXP',$tokens);
		// }
	}

	public static function setvar($key, $setvalue) {
		// try{
			$temp = variable_set($key, $setvalue);
			return;
		// } catch(Exception $e){
		// 	$tokens = array('{Error Message}' => $e->getMessage());
   		// 	Logs::logException('EXCEPTION','EXP',$tokens);
		// }
	}

	public static function setAppVar($key,$setvalue){
		return self::getResultset("INSERT INTO tbl_app_variables (variable_name,variable_value) VALUES ('".$key."','".$setvalue."');");

	}

	public static function deleteAppVar($key,$setvalue){
		return self::getResultset("delete from tbl_app_variables where variable_name = '".$key."' and variable_value = '".$setvalue."'");

	}

	public static function save($arrdata, $typename, $title, $nodeId=0, $UserId=0){

		global $user;
		$tmpuid = 0;
		$node = NULL;
		// try{

		if($nodeId == 0){
			$node = new StdClass();
			if($title!=''){
				$node->title = $title;
			}
			$node->type=$typename;
			$node->status = 1;
		}

		if($nodeId > 0){
			$node=self::load($nodeId);
			if(!$node) return 0;
			if($title!=''){
				$node->title = $title;
			}
		}

		if($UserId>0){
			$node->uid = $UserId;
		}else{
			$node->uid = $user->uid;
		}
		//NODE REFERENCE FIELD
		if(isset($arrdata['nid'])){
			foreach($arrdata['nid'] as $fname => $fvalue){
				$node->{$fname}[0]['nid'] = $fvalue;
			}
		}
		//USER REFERENCE FIELD
		if(isset($arrdata['uid'])){
			foreach($arrdata['uid'] as $fname => $fvalue){
				$node->{$fname}[0]['uid'] = $fvalue;
			}
		}
		//VALUE FIELD
		if(isset($arrdata['value'])){
			foreach($arrdata['value'] as $fname => $fvalue){
                if(is_array($fvalue)){
                    $node->{$fname} = $fvalue;
                }else{
                    $node->{$fname}[0]['value'] = $fvalue;
                }

			}
		}
		//OTHER FIELD
		if(isset($arrdata['other'])){
			foreach($arrdata['other'] as $fname => $fvalue){
				if($fvalue == 'true'){
					$node->{$fname} = true;
				}elseif($fvalue == 'false'){
					$node->{$fname} = false;
				}else{
					$node->{$fname} = $fvalue;
				}
			}
		}
		unset($arrdata);
		node_save($node);
		$returnval = $node->nid;
		unset($node);
		return $returnval;

		// } catch(Exception $e){
		// 	$tokens = array('{Error Message}' => $e->getMessage());
   		// 	Logs::logException('EXCEPTION','EXP',$tokens);
		// }
	}

	public static function load($nodeId=0) {
		// try{
			$node=node_load($nodeId, NULL, TRUE);
			if(!$node) return NULL;
			return $node;
		// } catch(Exception $e){
		// 	$tokens = array('{Error Message}' => $e->getMessage());
   		// 	Logs::logException('EXCEPTION','EXP',$tokens);
		// }
	}

	public static function delete($nodeId=0) {
	// try{
		$node=node_delete($nodeId);

		return;
	// } catch(Exception $e){
	// 	$tokens = array('{Error Message}' => $e->getMessage());
	// 	Logs::logException('EXCEPTION','EXP',$tokens);
	// }
}

	public static function getResultset($sql){
		//watchdog('backtrace','<pre>'.print_r(debug_backtrace(),1).'</pre>');
		return db_query($sql);
	}

	public static function getRow($resultSet,$returntype='object'){
		switch($returntype){
			case 'object':
				return db_fetch_object($resultSet);
				break;
			case 'array':
				return db_fetch_array($resultSet);
				break;
			default:
				return db_fetch_object($resultSet);
				break;

		}
		return;
	}

	public static function contentProfile($profileName){
		$user = self::user();
		return content_profile_load($profileName, $user->uid);
	}

	public static function message($msgtxt,$msgtype){
		drupal_set_message($msgtxt,$msgtype);
		return;
	}

	public static function redirect($path){
		drupal_goto($path);
		return;
	}
	public static function ifmodule($modulename){
		return module_exists($modulename);
	}

	public static function useraccess($permissionname){
		return user_access($permissionname);
	}

    public static function getModulePath($modulename){
        $path = drupal_get_path('module', $modulename);
        return $path;
    }

    public static function getSysRoles(){
        $roles = user_roles();
        $arrRolesToRemove = array("anonymous user", "authenticated user");
        foreach($arrRolesToRemove as $role){
            $key = array_search($role, $roles);
            if($key!==false){
                unset($roles[$key]);
            }
        }
        asort($roles);
        return $roles;
    }

    public static function getSpecificRoles($txtRoles){
        $roles = explode(",",$txtRoles);
        $allroles = self::getSysRoles();
        $returnarr = array();
        foreach($roles as $role){
            $key = array_search($role, $allroles);
            if($key!==false){
                $returnarr[$key] = $role;
            }
        }
		return $returnarr;
    }

	public static function getLogoPath($themename = 'theme_default'){
		$current_theme = ($themename == 'theme_default') ? variable_get($themename,'none') : $themename;
		$theme_array = variable_get('theme_'.$current_theme.'_settings','none');
		$logopath = '/'.$theme_array['logo_path'];
		return $logopath;
	}
	public static function getCountryById($country_id){
		$sql ="SELECT  * FROM cnx_countries where id = '$country_id' ";
            // $Db_query = new Db();
            $result = self::getResultset($sql);
			$data = self::getRow($result);
			return  $data;
	}

	public static function string_cleanup($main_string) {
		$string_after_clean_up = preg_replace("/[^a-z0-9]/i", "", $main_string);
		$string_after_clean_up = str_replace(" ", "", $string_after_clean_up);
		$string_after_clean_up =  strtoupper(trim($string_after_clean_up));
		return $string_after_clean_up;
	}

	public static function string_wrap($main_string,$transaction_id){
		$length = 122;
		if(strlen($main_string)<=$length)
		{
			echo $main_string;
		}
		else
		{
			$wrap_string=substr($main_string,0,$length) . '...<a href="#" onclick="show_cancellation_reason('.$transaction_id.')">More</a>';
			echo $wrap_string;
		}
	}


	public static function get_grace_period_config(){
		$sql = "SELECT * FROM cnx_grace_period_config";
		$result = self::getResultset($sql);

		while ($row = db_fetch_object($result)) {
			$data[$row->transaction_status] = array(
			  'id' => $row->id,
			  'transaction_status' => $row->transaction_status,
			  'is_grace_period_active' => $row->is_grace_period_active,
			  'grace_period_time_in_minutes' => $row->grace_period_time_in_minutes,
			  'is_send_mail' => $row->is_send_mail
			);
		}
		return  $data;
	}

	public static function get_grace_period_notify_schedule($gp_config_id){
		$sql = "SELECT * FROM cnx_grace_period_notify_schedule where gp_config_id = ".$gp_config_id;
		$result = self::getResultset($sql);

		while ($row = db_fetch_object($result)) {
			$data[] = array(
			  'id' => $row->id,
			  'schedule_time' => $row->schedule_time,
			  'email_template_key' => $row->email_template_key
			);
		}
		return  $data;
	}





    // public static function applyTemplate($modulename,$fileName,$vars){
    //     ob_start();
    //     $path = self::getModulePath($modulename);
    //     include($path."/templates/".$fileName);
    //     $contents = ob_get_contents();
    //     ob_end_clean();
    //     return $contents;
    // }
	// public static function applyTemplateFile($templatPath,$fileName,$vars){
    //     ob_start();
    //     include($templatPath . $fileName);
    //     $contents = ob_get_contents();
    //     ob_end_clean();
    //     return $contents;
    // }
}