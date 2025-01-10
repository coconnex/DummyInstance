<?php

namespace Coconnex\API\Masters\Currency\Models\EntityModels;
use Coconnex\DBFactory\Db;
include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/DBFactory/Db.Class.php");

class CurrencyEntityModel extends Db
{
    public $id;
    public $iso_alpha_3_code;
    public $name;
    public $utf8_symbol_char;
    public $utf8_symbol_deci;
    public $utf8_symbol_hex;
    public $utf8_symbol_entity;
    public $deleted;

    public function __construct($uid, $id = null)
    {
        if (is_numeric($id)) $this->id = $id;
        $this->configureMeta(
            $uid,
            'cnx_currency',
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
        $delete_arr['table'] = 'cnx_currency';
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
