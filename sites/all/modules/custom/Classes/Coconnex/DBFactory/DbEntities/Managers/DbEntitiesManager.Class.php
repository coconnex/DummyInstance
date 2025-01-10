<?php

namespace Coconnex\DBFactory\DbEntities\Managers;

class DbEntitiesManager
{
    public $entities = array();
    protected $app_key;
    protected $force;

    public function __construct($app_key, $force = false)
    {
        $this->app_key = $app_key;
        $this->force = $force;
    }

    public function get_template($tpl_relative_path)
    {
        $tpl_path = \dirname(\dirname(__FILE__)) . $tpl_relative_path;
        if (\file_exists($tpl_path)) return $tpl_path;
        return null;
    }

    public function save_class($content, $class_name, $vendor)
    {
        $content = "<?php\r\n" . $content;
        $class_file_name = $this->get_class_file_name($class_name);
        $path = $this->get_class_path($vendor) . $class_file_name;
        file_put_contents($path, $content);
    }

    public function get_class_file_name($name)
    {
        return \sprintf("%s_ENTITY_MODEL", $name) . ".Class.php";
    }

    public function get_class_path($vendor)
    {
        return dirname(dirname(__FILE__)) . "/Applications/" . $this->app_key . "/" . $vendor . "/Entities/";
    }

    public function class_file_exists($name, $vendor)
    {
        $class_file_name = $this->get_class_file_name($name);
        $class_path = $this->get_class_path($vendor);
        $class_full_path = $class_file_name . $class_path;
        return \file_exists($class_full_path);
    }
}
