<?php

namespace Coconnex\DBFactory\Vendors\Drupal\Dnode;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Interfaces/I_DB_Operation.Interface.php");

use Coconnex\DBFactory\Interfaces\I_DB_Operation;

class Db extends \stdClass implements I_DB_Operation
{
    public $nid;
    public $vid;
    public $type;
    public $uid;
    public $status = 1;
    public $title;

    public function __construct($nid = null)
    {

        if (is_numeric($nid)) {
            $this->nid = $nid;
            $this->load();
        }
        if (is_numeric($nid) && !is_numeric($this->nid)) {
            // $this->success = false;
            // $this->message = "Invalid " . $this->type . "reference passed";
            // set and return a failure message here
            return;
        }
    }

    public function load($nid = null)
    {
        if (!is_numeric($nid)) $nid = $this->nid;
        if (is_numeric($nid) &&  function_exists("node_load")) {
            $n = node_load($this->nid, false, true);
            if ($this->type !== $n->type) {
                $n = null;
                $this->nid = null;
                return;
            }
        }


        $n_arr = (array) $n;
        $this_arr = (array) $this;
        foreach ($this_arr as $key => $value) {
            if (key_exists($key, $n_arr)) $this->{$key} = $n_arr[$key];
        }
        $n = null;
        $n_arr = null;
        $this_arr = null;
    }

    public function save()
    {
        if (function_exists("node_save")) {
            if (is_numeric($this->nid) && !is_numeric($this->vid)) $this->vid = $this->vid;
            node_save($this);
        }

        if (is_numeric($this->nid)) {
            // $this->success = true;
            // $this->message = "Transaction " . $this->type . " saved successfully";
            // Set and return a success message here
            return $this->nid;
        } else {
            // $this->success = false;
            // $this->message = "Transaction " . $this->type . " could not be saved";
            // Set and return a failure message here
            return false;
        }
    }

    public function add()
    {
        $this->save();
    }

    public function modify()
    {
        $this->save();
    }

    public function remove()
    {
        if (is_numeric($this->nid) &&  function_exists("node_delete")) {
            return node_delete($this->nid);
        }
    }

    public function set_prop(&$prop, $value, $key = null)
    {
        if ($key !== null) {
            $prop[0][$key] = $value;
            return;
        }
        if (!is_array($prop)) {
            $prop = array('0' => array('value' => $value));
            return;
        }
        foreach ($prop as $ik => $iv) {
            if (!is_array($iv)) $iv = array('value' => '');
            foreach ($iv as $pk => $pv) {
                $prop[$ik][$pk] = $value;
                break;
            }
            break;
        }
    }

    public function get_prop($value)
    {
        if (!is_array($value)) return $value;
        foreach ($value as $iv) {
            if (!is_array($iv)) {
                return $value;
            }
            foreach ($iv as $pv) {
                return $pv;
            }
            break;
        }
    }
}
