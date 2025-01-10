<?php

namespace Coconnex\DBFactory\DbEntities\Managers;

require_once(dirname(dirname(__FILE__)) . "/Interfaces/I_DbEntities_Manager.Interface.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Vendors/MySql/Db.Class.php");
require_once(dirname(__FILE__) . "/DbEntitiesManager.Class.php");

use Coconnex\DBFactory\DbEntities\Interfaces\I_DbEntities_Manager;
use Coconnex\DBFactory\Vendors\MySql\Db;

class MySqlEntitiesManager extends DbEntitiesManager implements I_DbEntities_Manager
{
    const TPL_RELATIVE_PATH = "/EntityTemplates/mysql_entity.tpl";
    const VENDOR = "MySql";
    protected $tpl_meta_array = array('uid' => '$uid', 'tablename' => '', 'primarykey' => '');
    protected $tpl_meta_names_array = array('created_on', 'created_by', 'modified_on', 'modified_by');
    public $allowed_pattern = "/^(CNX|TBL|SF|CRM|USERS|ROLE)(.*)/";

    public function __construct($app_key, $force = false)
    {
        parent::__construct($app_key, $force);
        $this->set_entities();
    }

    public function set_entities()
    {
        $db = new Db();
        $result = $db->getResultset("SHOW TABLES");
        while ($t = \mysql_fetch_array($result)) {;
            $this->entities[$t[0]] = $this->get_properties($t[0]);
        }
    }

    public function get_properties($entity)
    {
        $db = new Db();
        $result = $db->getResultset("SHOW COLUMNS FROM " . $entity);
        $props = array();
        while ($p = \mysql_fetch_assoc($result)) {;
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
                if (\preg_match($this->allowed_pattern, $name) && (!$this->class_file_exists($name, self::VENDOR) || $this->force)) {
                    $_props = $this->get_props($props, $entity);
                    //debug($_props);
                    $props = $_props['props'];
                    $meta = $_props['meta'];
                    $meta['tablename'] = "'" . $entity . "'";
                    $meta_join = \implode(",", $meta);
                    $pri_key = str_replace("'", "", $meta['primarykey']);
                    $content = \sprintf($tpl, $this->app_key, $name, $props, $pri_key, $meta_join);
                    $this->save_class($content, $name, self::VENDOR);
                    echo $name . " | " . \preg_match($this->allowed_pattern, $name)  . "\r\n";
                }
            }
        }
    }

    public function get_props($props, $entity)
    {
        $ret_props = array();
        $ret_props['props'] = "";
        $ret_props['meta'] = $this->tpl_meta_array;
        foreach ($props as $index => $prop) {
            if (!\in_array($prop['Field'], $this->tpl_meta_names_array)) $ret_props['props'] .= "\tpublic $" . $prop['Field'] . ";\r\n";
            if ($prop['Key'] === 'PRI') $ret_props['meta']['primarykey'] = "'" . $prop['Field'] . "'";
            if (\in_array($prop['Field'], $this->tpl_meta_names_array)) $ret_props['meta'][$prop['Field']] = "'" . $prop['Field'] . "'";
        }
        return $ret_props;
    }
}
