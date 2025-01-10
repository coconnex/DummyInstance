<?php

namespace Coconnex\Utils\Handlers;

class TemplateHandler
{

    public static function applyTemplateFile($templatPath, $fileName, $vars)
    {
        ob_start();
        include($templatPath . $fileName);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
}
