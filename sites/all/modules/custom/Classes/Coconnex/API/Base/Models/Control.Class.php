<?php

namespace Coconnex\API\Base\Models;

require_once(dirname(dirname(__FILE__)) . "/Models/Template.Class.php");


use Coconnex\API\Base\Models\Template;

Class Control{
    public $template;
    public $id;
    public $name;
    public $link;
    public $value;
    public $styles;
    public $base_url;
    public $html;
    public $jsfunction;

    public function __construct($id = "", $name = "", $value = "", $link = "", $styles = "", $jsfunction = "", $base_url = ""){
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->link = $link;
        $this->styles = $styles;
        $this->base_url = $base_url;
        $this->jsfunction = $jsfunction;
        $this->template = new Template();
    }

    public function get_html(){
        if(!empty($this->template->path) && !empty($this->template->file)){
            return $this->template->render((array)$this);
        }else{
            return $this->get_default_html();
        }
    }

    private function get_default_html(){

        $link = (!empty($this->base_url)) ? $this->base_url.'/'.$this->link : $this->link;

        $button = '<button type="button" id="%s" class="%s" value="%s" link="%s" onclick="%s(\'%s\')">%s</button>';
        return sprintf($button, $this->id.$this->value, $this->styles, $this->value, $link, $this->jsfunction, $this->id.$this->value, $this->name);
    }

    public function set_html(){
        $this->html = $this->get_html();
    }
}