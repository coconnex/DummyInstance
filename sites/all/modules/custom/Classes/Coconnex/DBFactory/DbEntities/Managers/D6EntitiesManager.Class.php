<?php

namespace Coconnex\DBFactory\DbEntities\Managers;

require_once(dirname(dirname(__FILE__)) . "/Interfaces/I_DbEntities_Manager.Interface.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Vendors/MySql/Db.Class.php");
require_once(\dirname(\dirname(\dirname(__FILE__))) . "/Vendors/Drupal/Dnode/Db.Class.php");
require_once(\dirname(__FILE__) . "/DbEntitiesManager.Class.php");

use Coconnex\DBFactory\DbEntities\Interfaces\I_DbEntities_Manager;
use Coconnex\DBFactory\Vendors\Drupal\Dnode\Db as Dnode;
use Coconnex\DBFactory\Vendors\MySql\Db;

class D6EntitiesManager extends DbEntitiesManager implements I_DbEntities_Manager
{
    const TPL_RELATIVE_PATH = "/EntityTemplates/d6_entity.tpl";
    const VENDOR = "D6";

    public function __construct($app_key, $force = false)
    {
        parent::__construct($app_key, $force);
        $this->set_entities();
    }

    public function set_entities()
    {
        $db = new Db();
        $sql = "SELECT type FROM node_type";
        $result = $db->getResultset($sql);
        while ($t = \mysql_fetch_array($result)) {
            $this->entities[$t['type']] = $this->get_properties($t['type']);
        }
    }

    public function get_properties($entity)
    {
        $db = new Db();
        $sql = "SELECT cnfi.field_name
                ,cnf.module
                ,cnf.db_columns
                FROM content_node_field_instance cnfi
                LEFT JOIN content_node_field cnf ON cnf.field_name = cnfi.field_name
                WHERE type_name = '%s'";
        $query = \sprintf($sql, $entity);
        $result = $db->getResultset($query);
        $props = array();
        while ($p = \mysql_fetch_assoc($result)) {;
            if (isset($p['db_columns'])) $p['db_columns'] = \unserialize($p['db_columns']);
            $props[] = $p;
        }
        return $props;
    }

    public function generate()
    {
        $tpl_path = $this->get_template(self::TPL_RELATIVE_PATH);
        if ($tpl_path !== null) {
            $tpl = \file_get_contents($tpl_path);
            foreach ($this->entities as $entity => $props) {
                $name = strtoupper(trim($entity));
                if (!$this->class_file_exists($name, self::VENDOR) || $this->force) {
                    $props = $this->get_props($props, $entity);
                    $content = \sprintf($tpl, $this->app_key, $name, $props);
                    echo $name . "\r\n";
                    echo $content . "\r\n";
                    $this->save_class($content, $name, self::VENDOR);
                }
            }
        }
    }

    public function get_props($props, $entity)
    {
        $ret_props = array();
        $ret_props[] = "\tpublic $" . "type = '" . trim($entity) . "';";
        foreach ($props as $index => $prop) {
            $val = sprintf("array(array(%s))", $this->get_db_columns($prop['db_columns']));
            $ret_props[] = "\tpublic $" . $prop['field_name'] . " = " . $val . ";";
        }
        return implode("\r\n", $ret_props);
    }

    protected function get_db_columns($db_columns)
    {
        $cols = array();
        foreach ($db_columns as $key => $defs) {
            $cols[] = sprintf("'%s' => ''", $key);
        }
        return implode(",", $cols);
    }
}
