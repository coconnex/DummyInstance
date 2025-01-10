<?php

namespace Coconnex\API\Masters\Country\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/CountryEntityModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/Country.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Currency/Managers/CurrencyManager.Class.php");

use Coconnex\API\Masters\Country\Models\Country;
use Coconnex\API\Masters\Currency\Managers\CurrencyManager;
use Coconnex\API\Masters\Country\Models\EntityModels\CountryEntityModel;

class CountryManager
{
    public $country;
    public $uid;


    public function __construct($country, $uid)
    {
        $this->uid = $uid;
        if ($country instanceof Country) $this->country = $country;
        if (is_numeric($country)) $this->country = new CountryEntityModel($this->uid, $country);
    }

    public function delete()
    {
        $this->country->delete();
    }

    public function remove()
    {
        $this->country->remove();
    }

    public function revoke()
    {
        $this->country->revoke();
    }
    public function save_country()
    {
        if ($this->country instanceof Country) {

            $currency_id = null;
            $currency_obj = new CurrencyManager($this->country->currency_id, $this->uid);
            $result = $currency_obj->save_currency();

            if ($result === true) {
                $currency_id = $currency_obj->currency->id;
            } else {
                $currency_id = $this->country->currency_id;
            }
            $country_entity =  new CountryEntityModel($this->uid, $this->country);
            $country_entity->id  = $this->country->id;
            $this->country->currency_id = $currency_id;

            if (isset($this->country->id)) $country_entity->id = $this->country->id;
            if (isset($this->country->name)) $country_entity->name  = $this->country->name;
            if (isset($this->country->country_name)) $country_entity->country_name  = $this->country->country_name;
            if (isset($this->country->iso_code)) $country_entity->iso_code  = $this->country->iso_code;
            if (isset($this->country->region)) $country_entity->region  = $this->country->region;
            if (isset($this->country->iso_alpha_3_code)) $country_entity->active  = $this->country->active;
            if (isset($this->country->active)) $country_entity->iso_alpha_3_code  = $this->country->iso_alpha_3_code;
            if (isset($this->country->iso_urn)) $country_entity->iso_urn  = $this->country->iso_urn;
            if (isset($this->country->sub_continent)) $country_entity->sub_continent  = $this->country->sub_continent;
            if (isset($this->country->world_region)) $country_entity->world_region  = $this->country->world_region;
            if (isset($this->country->formal_name)) $country_entity->formal_name  = $this->country->formal_name;
            if (isset($this->country->international_telephone_code)) $country_entity->international_telephone_code  = $this->country->international_telephone_code;
            if (isset($this->country->deleted)) $country_entity->deleted  = $this->country->deleted;

            $new_result = $country_entity->save();

            if ($new_result > 0) {
                $this->country =  $country_entity;
                return true;
            } else {
                return false;
            }
        }
    }
    public function get_country()
    {

        if ($this->country instanceof CountryEntityModel) {
            $country_response = new Country();
            $country_response->id  = $this->country->id;
            $country_response->name  = $this->country->name;
            $country_response->country_name  = $this->country->country_name;
            $country_response->iso_code  = $this->country->iso_code;
            $country_response->region  = $this->country->region;
            $country_response->iso_alpha_3_code  = $this->country->iso_alpha_3_code;
            $country_response->active  = $this->country->active;
            $country_response->iso_urn  = $this->country->iso_urn;
            $country_response->sub_continent  = $this->country->sub_continent;
            $country_response->world_region  = $this->country->world_region;
            $country_response->formal_name  = $this->country->formal_name;
            $country_response->currency = $this->get_currency();
            $country_response->international_telephone_code  = $this->country->international_telephone_code;
            $country_response->deleted  = $this->country->deleted;

            return $country_response;
        }
    }
    public function get_currency()
    {
        $curr_mgr = new CurrencyManager($this->country->currency_id, $this->uid);
        return $curr_mgr->get_currency();
    }
}
