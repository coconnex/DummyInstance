<?php

namespace Coconnex\API\IFPSS\Registration\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/RegistrationEntityModel.Class.php");

use Coconnex\API\IFPSS\Registration\Models\EntityModels\RegistrationEntityModel;


class RegistrationManager
{
    public $registration;
    protected $uid;


    public function __construct($registration, $uid)
    {
        $this->uid = $uid;
        if ($registration instanceof RegistrationEntityModel) $this->registration = $registration;
        if (is_numeric($registration)) {
            $this->registration = new RegistrationEntityModel($this->uid, $registration);
        }
    }

    public function remove($id)
    {
        $registration_entity = new RegistrationEntityModel($this->uid, $id);
        $registration_entity->RemoveRecord($id);
    }

    public function revoke($id)
    {
        $registration_entity = new RegistrationEntityModel($this->uid, $id);
        $registration_entity->RevokeRecord($id);
    }

    public function save_registration()
    {
        
        // if ($this->registration instanceof RegistrationEntityModel) {
        $registration_entity = new RegistrationEntityModel($this->uid, $this->registration->id);
       
        if (isset($this->registration->type)) $registration_entity->type  = $this->registration->type;
        if (isset($this->registration->method)) $registration_entity->method  = $this->registration->method;
        if (isset($this->registration->data)) $registration_entity->data = $this->registration->data;
        if (isset($this->registration->backend_ref) && is_numeric($this->registration->backend_ref ))
            $registration_entity->backend_ref = $this->registration->backend_ref;
            // debug($registration_entity,1);
        $result = $registration_entity->save();
        if ($result > 0) {
            // if (!is_numeric($registration_entity->id)) $registration_entity->id = $result;
            // $this->registration= $registration_entity;
            return $result;
        } else {
            return false;
        }
        // }
        // return false;
    }

    public function get_registration()
    {
        $registration_response = new RegistrationEntityModel();
        $registration_response->id  = $this->registration->id;
        $registration_response->type = $this->registration->type;
        $registration_response->method = $this->registration->method;
        $registration_response->data  = $this->registration->data;
        $registration_response->backend_ref  = $this->registration->backend_ref;
        return $registration_response;
    }
}
