<?php

namespace Coconnex\API\Masters\Country\Models\EntityModels;

include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;

class CountryEntityModel extends Db
{
    public $id;
    public $name;
    public $country_name;
    public $iso_code;
    public $region;
    public $iso_alpha_3_code;
    public $active;
    public $iso_urn;
    public $sub_continent;
    public $world_region;
    public $formal_name;
    public $currency_id;
    public $international_telephone_code;
    public $deleted;

    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_countries',
            'id',
            'created_on',
            'created_by',
            'modified_on',
            'modified_by'
        );
        parent::__construct($id);
    }
    public function delete()
    {
        $delete_arr = array();
        $delete_arr['table'] = 'cnx_countries';
        $delete_arr['conditions'] = ' id = ' . $this->id;
        $this->DeleteRecord($delete_arr);
    }

    public function remove($id = null)
    {
        $this->RemoveRecord($this->id);
    }

    public function revoke()
    {
        $this->RevokeRecord($this->id);
    }
}
