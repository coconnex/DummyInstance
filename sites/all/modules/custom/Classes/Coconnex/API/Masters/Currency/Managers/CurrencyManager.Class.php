<?php

namespace Coconnex\API\Masters\Currency\Managers;

require_once(dirname(dirname(__FILE__)) . "/Models/EntityModels/CurrencyEntityModel.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Models/Currency.Class.php");

use Coconnex\API\Masters\Currency\Models\Currency;
use Coconnex\API\Masters\Currency\Models\EntityModels\CurrencyEntityModel;

class CurrencyManager
{
    public $currency;
    public $uid;


    public function __construct($currency, $uid)
    {
        $this->uid = $uid;
        if ($currency instanceof Currency) $this->currency = $currency;
        if (is_numeric($currency)) $this->currency = new CurrencyEntityModel($this->uid, $currency);
    }

    public function delete()
    {
        $this->currency->delete();
    }

    public function remove()
    {
        $this->currency->remove();
    }

    public function revoke()
    {
        $this->currency->revoke();
    }
    public function save_currency()
    {
        if ($this->currency instanceof Currency) {
            $currency_entity =  new CurrencyEntityModel($this->uid, $this->currency);
            $currency_entity->id = $this->currency->id;
            if (isset($this->currency->iso_alpha_3_code)) $currency_entity->iso_alpha_3_code = $this->currency->iso_alpha_3_code;
            if (isset($this->currency->name)) $currency_entity->name = $this->currency->name;
            if (isset($this->currency->utf8_symbol_char)) $currency_entity->utf8_symbol_char = $this->currency->utf8_symbol_char;
            if (isset($this->currency->utf8_symbol_deci)) $currency_entity->utf8_symbol_deci = $this->currency->utf8_symbol_deci;
            if (isset($this->currency->utf8_symbol_hex)) $currency_entity->utf8_symbol_hex = $this->currency->utf8_symbol_hex;
            if (isset($this->currency->utf8_symbol_entity)) $currency_entity->utf8_symbol_entity = $this->currency->utf8_symbol_entity;
            if (isset($this->currency->deleted)) $currency_entity->deleted = $this->currency->deleted;

            $new_result = $currency_entity->save();

            if ($new_result > 0) {
                $this->currency =  $currency_entity;
                return true;
            } else {
                return false;
            }
        }
    }
    public function get_currency()
    {

        if ($this->currency instanceof CurrencyEntityModel) {
            $currency_response = new Currency();
            $currency_response->id = $this->currency->id;
            $currency_response->iso_alpha_3_code = $this->currency->iso_alpha_3_code;
            $currency_response->name = $this->currency->name;
            $currency_response->utf8_symbol_char = $this->currency->utf8_symbol_char;
            $currency_response->utf8_symbol_deci = $this->currency->utf8_symbol_deci;
            $currency_response->utf8_symbol_hex = $this->currency->utf8_symbol_hex;
            $currency_response->utf8_symbol_entity = $this->currency->utf8_symbol_entity;
            $currency_response->deleted = $this->currency->deleted;

            return $currency_response;
        }
    }
}
