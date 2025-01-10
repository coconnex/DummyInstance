<?php

namespace Coconnex\API\IFPSS\Exhibitor\Managers;

use Coconnex\API\IFPSS\Exhibitor\Models\EntityModels\ExhibitorEntityModel;
use Coconnex\API\IFPSS\Exhibitor\Models\RequestModels\ExhibitorRequestModel;
use Coconnex\API\IFPSS\Exhibitor\Models\ResponseModels\ExhibitorResponseModel;
use Coconnex\DBFactory\Db;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Registration\Managers\APIRegistrationManager;

require_once(dirname(dirname(__FILE__)) . "/Models/ResponseModels/ExhibitorResponseModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/RequestModels/ExhibitorRequestModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/ExhibitorEntityModel.Class.php");


class CNXExhibitorManager {
    public $exhibitor;
    protected $uid;

    public function __construct($exhibitor, $uid)
    {
        $this->uid = $uid;
        $this->exhibitor = null;
        if ($exhibitor instanceof ExhibitorRequestModel || $exhibitor instanceof ExhibitorEntityModel) $this->exhibitor = $exhibitor;
        if (is_numeric($exhibitor) && $exhibitor>0) $this->exhibitor = new ExhibitorEntityModel($this->uid, $exhibitor);
        if (is_numeric($exhibitor) && $exhibitor==0 && $uid>0) $this->exhibitor =  $this->getExhibitorByUserRef();
    }

    public function save_exhibitor()
    {
        if ($this->exhibitor instanceof ExhibitorRequestModel) {

            $exhibitor_entity = new ExhibitorEntityModel($this->uid,$this->exhibitor->id);
            if (isset($this->exhibitor->id)) $exhibitor_entity->id = $this->exhibitor->id;
            if (isset($this->exhibitor->company_name)) $exhibitor_entity->company_name = $this->exhibitor->company_name;
            if (isset($this->exhibitor->registration_id))$exhibitor_entity->registration_id =$this->exhibitor->registration_id;
            if (isset($this->exhibitor->user_ref_id)) $exhibitor_entity->user_ref_id = $this->exhibitor->user_ref_id;
            if (isset($this->exhibitor->external_ref_id))$exhibitor_entity->external_ref_id = $this->exhibitor->external_ref_id;
            if (isset($this->exhibitor->is_validated))$exhibitor_entity->is_validated = $this->exhibitor->is_validated;
            if (isset($this->exhibitor->enabled))$exhibitor_entity->enabled =$this->exhibitor->enabled;
            $result = $exhibitor_entity->save();
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function get_exhibitor()
    {
        if ($this->exhibitor instanceof ExhibitorEntityModel) {
            $exhibitor_response = new ExhibitorResponseModel();
            $exhibitor_response->id = $this->exhibitor->id;
            $exhibitor_response->company_name = $this->exhibitor->company_name;
            $exhibitor_response->registration_id = $this->exhibitor->registration_id;
            $exhibitor_response->user_ref_id = $this->exhibitor->user_ref_id;
            $exhibitor_response->external_ref_id = $this->exhibitor->external_ref_id;
            $exhibitor_response->is_validated = $this->exhibitor->is_validated;
            $exhibitor_response->enabled =$this->exhibitor->enabled;
            return $exhibitor_response;
        }
    }

    public function getExhibitorByUserRef(){

        $sql = "select id from cnx_exhibitor where user_ref_id = " .$this->uid;
        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        if($result){
            foreach($result as $res){
                return new ExhibitorEntityModel($this->uid, $res['id']);
            }
        }
        return;
    }

    public function getExhibitorProfileStatus(){
        $sub_sector = 0;
        $mgr = new APIRegistrationManager($this->exhibitor->external_ref_id);
        $exhibitor_details = $mgr->exhibitor;

        if(is_array($exhibitor_details) && !empty($exhibitor_details)){
                $sub_sector = $exhibitor_details['field_industry_subsector'];
        }
        return (!is_numeric($sub_sector) || $sub_sector == 0) ? false : true;
    }



}