<?php
namespace Coconnex\API\Base\Models;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Utils/Handlers/TemplateHandler.Class.php");

use Coconnex\Utils\Handlers\TemplateHandler;

Class Template{
    public $path;
    public $file;

    public function __construct($path = "", $file = "")
    {
        $this->path = $path;
        $this->file = $file;
    }

    public function render($vars){
        if(!empty($this->path) && !empty($this->file)){
            return TemplateHandler::applyTemplateFile($this->path, $this->file,$vars);
        }
        return null;
    }
}