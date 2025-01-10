<?php

namespace Coconnex\Utils\Handlers;

require_once(dirname(__FILE__) . "/TemplateHandler.Class.php");

use Coconnex\Utils\Handlers\TemplateHandler;

class ProcessingPopupHandler
{
    public static $processing_popup_tpl_folder_path = 'sites/all/themes/exhibitor_zone/templates/';
    public static $processing_popup_tpl_file = 'processing.tpl.php';

    public static function render()
    {
       $vars = "";
       print self::get_html($vars);
    }

    protected static function get_html($vars = array())
    {
        $file = self::$processing_popup_tpl_folder_path . self::$processing_popup_tpl_file;
        if (\file_exists($file)) {
            return TemplateHandler::applyTemplateFile(self::$processing_popup_tpl_folder_path, self::$processing_popup_tpl_file, $vars);
        }
    }
}
