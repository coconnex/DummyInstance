<?php
namespace Coconnex\DBFactory\Vendors\Drupal\D6;

include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/DBFactory.Class.php");

use Coconnex\DBFactory\DBFactory;

class D6User {

    protected $uid;
    protected $name;
    protected $pass;
    protected $mail;
    protected $status;
    protected $init;
    protected $roles;

    /**
     * 
     * @param type $uid  = Drupal 6 User ID. For a new user, do not initialise with a uid. For an existing user, initialise with a uid.
     */
    function __construct($uid = -1) {
    	$this->uid = $uid;
    	$this->name = "";
    	$this->pass = "";
    	$this->mail = "";
    	$this->status = 1;
    	$this->init = "";
    	$this->roles = array();
    	if($this->uid>0){
            $this->init();
        }
       
    }

    /**
     * 
     * @param type none: 
     * @todo Populates the class variables from the passed array
     */
    public function overwriteData($arrData = array()) {
    	if(count($arrData)>0){
    		foreach($arrData as $field => $value){
				if(property_exists($this, $field)){
                    $this->{$field} = $value;
                }
			}
            if(!isset($arrData['roles'])){
                $this->roles = array_keys($this->roles);
            }
		}
        return;
    }

    /**
     * 
     * @todo Get Drupal's user object
     */
    public function getObj() {
        $u = null;
        if($this->uid>0){
            $u = user_load($this->uid);
            $this->name = $u->name;
            $this->pass = $u->pass;
            $this->mail = $u->mail;
            $this->status = $u->status;
            $this->init = $u->init;
            $this->roles = $u->roles;    	
        }
        return $u;
    }

    /**
     * 
     * @todo Get Drupal's user object
     */
    public function init() {
        $userObj = $this->getObj();
        unset($userObj);
        return;
    }

    /**
     * 
     * @todo Get Drupal's user object
     */
    public function initByName($username) {
        if(trim($username) != ""){
            $this->name = trim($username);
            $db = DBFactory::getObject('mysql');           
            $sql = "SELECT uid FROM users WHERE name= '".$this->name."' LIMIT 0,1";
            $rs = $db->getResultset($sql);
            while($row = $db->getResultRow($rs)){
                $this->uid = $row->uid;
                $this->init();
            }
            unset($rs);
		}
        return;
    }

    /**
     * 
     * @todo Block a Drupal user
     */
    public function block() {
    	$userObj = $this->getObj();
        $userstatus = array('status' => 0); 
		$status = user_save($userObj, $userstatus);   
        drupal_set_message("User blocked successfully", "info");
        unset($userObj,$userstatus);
        return $status;
    }

    /**
     * 
     * @todo Activate a Drupal user
     */
        public function activate() {
    	$userObj = $this->getObj();
        $userstatus = array('status' => 1); 
		$status = user_save($userObj, $userstatus);
		drupal_set_message("User activated successfully", "info");
        unset($userObj,$userstatus);
        return $status;
    }

    /**
     * 
     * @todo Get name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @param type $data = Data passed in an array for update purposes.
     */
    public function edit($arrData = array()) {
        $userObj = $this->getObj();
        $this->overwriteData($arrData);
        
        $allRoles = user_roles(); // loads the roles table into an array
        
		$rolesToAdd=array(DRUPAL_AUTHENTICATED_RID => 'authenticated user');
		$role = $this->getRoles();
         
		//THE BELOW FOR BLOCK GETS THE ROLE WITH ID FROM THE ROLE ID PASSED AS VALUE. FOR OTHER IMPLEMENTATIONS COMMENT THIS BLOCK.
        foreach($role as $value){
            $rolesToAdd[$value]=$allRoles[$value];
        }
        //        //THE BELOW FOR BLOCK GETS THE ROLE ID FROM THE ROLE NAME PASSED. FOR OTHER IMPLEMENTATIONS UNCOMMENT THIS BLOCK.
        //        foreach($role as $key =>$value){
        //            if($k = (array_search($value, $allRoles))){
        //                    $rolesToAdd[$k]=$allRoles[$k];
        //            }
        //        }
                
		$this->setRoles($rolesToAdd);
		$data = $this->getObjData();
		
		$curruser = user_save($userObj, $data);
		unset($data,$allRoles,$rolesToAdd,$role);
        return;
    }
    
    /**
     * 
     * @param type $data = Data passed in an array for add purposes.
     */
    public function add($arrData, $checkIfExists = false) {
        $returnuid = 0;
        $this->overwriteData($arrData);
        $allRoles = user_roles(); // loads the roles table into an array
        $rolesToAdd=array(DRUPAL_AUTHENTICATED_RID => 'authenticated user');
        $role = $this->getRoles();
        //THE BELOW FOR BLOCK GETS THE ROLE WITH ID FROM THE ROLE ID PASSED AS VALUE. FOR OTHER IMPLEMENTATIONS COMMENT THIS BLOCK.
        if($this->pass==""){
            $this->pass = user_password();
        }
        foreach($role as $value){
            $rolesToAdd[$value]=$allRoles[$value];
        }
        //        //THE BELOW FOR BLOCK GETS THE ROLE ID FROM THE ROLE NAME PASSED. FOR OTHER IMPLEMENTATIONS UNCOMMENT THIS BLOCK.
        //        foreach($role as $key =>$value){
        //            if($k = (array_search($value, $allRoles))){
        //                    $rolesToAdd[$k]=$allRoles[$k];
        //            }
        //        }
        $this->setRoles($rolesToAdd);
        $data = $this->getObjData();
        if($checkIfExists){
            $db = DBFactory::getObject('mysql');   
            $sql = "SELECT uid FROM users WHERE name = '".$data['name']."'";
            $rs =$db->getResultset($sql);
            while($row = $db->getResultRow($rs)){
                $returnuid = $row->uid;
            }
            unset($rs);
            if($returnuid>0){
                //KS:25-Feb-2024::Feature to return a message can be implemented
            }
        }
        if($returnuid==0){
            $curruser = user_save(null, $data);
            $returnuid = $curruser->uid;
        }
        unset($data,$allRoles,$rolesToAdd,$role);
        return $returnuid;
    }
    
    /**
     * 
     * @param type $txt = Query for data generation. This returns a drupal resultset.
     */
    public function getObjData() {
		return array(
			 		'uid' => $this->uid,
			 		'name' => $this->name,
			 		'pass' => $this->pass, 
			  		'mail' => $this->mail, 
			  		'status' => $this->status,
			  		'init' => $this->init, 
			  		'roles' => $this->roles
					); 
	}    
    
    /**
     * 
     * @param type $roles = Set Roles to update
     */
    protected function setRoles($roles) {
        $this->roles = $roles;
        return;
    }
    
    /**
     * 
     * @param type = Get Roles 
     */
    public function getRoles() {
        return $this->roles;
    }     
    
    /**
     * 
     * @param type = Get Roles 
     */
    public function getMemberOnlyRoles() {
        $roles = $this->roles;
        $arrRolesToRemove = array("anonymous user", "authenticated user");
        foreach($arrRolesToRemove as $role){
            $key = array_search($role, $roles);
            if($key!==false){
                unset($roles[$key]);
            }
        }
        return $roles;
    }     
    /**
     * 
     * @param type = Get Role Ids 
     */
    public function getRoleIds() {
        
        return array_keys($this->roles);
    }    
    /**
     * 
     * @param type = Get Roles 
     */
    public function getStatus() {
        return $this->status;
    }
    
}
