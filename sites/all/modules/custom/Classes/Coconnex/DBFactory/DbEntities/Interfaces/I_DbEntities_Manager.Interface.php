<?php

namespace Coconnex\DBFactory\DbEntities\Interfaces;

interface I_DbEntities_Manager
{
    public function __construct($app_key, $force = false);
    public function set_entities();
    public function get_properties($entity);
    public function generate();
    public function get_template($tpl_relative_path);
    public function get_props($props, $entity);
    public function save_class($content, $class_name, $vendor);
    public function get_class_file_name($name);
    public function get_class_path($vendor);
    public function class_file_exists($name, $vendor);
}
