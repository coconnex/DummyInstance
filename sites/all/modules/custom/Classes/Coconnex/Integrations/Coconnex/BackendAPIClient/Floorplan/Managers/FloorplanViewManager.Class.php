<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\Managers;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\FloorplanView;

require_once(dirname(dirname(__FILE__)) . "/FloorplanView.Class.php");

class FloorplanViewManager
{
    protected $arr_view;
    protected $hall_id;
    public $response;

    public function __construct($hall_id)
    {
        $this->arr_view = array();
        $this->hall_id = $hall_id;
        $this->process();
    }

    /**
     * process()
     *
     * Processes the call required to fetch the list of products with their pricing.
     * @return void
     */
    protected function process(){

        $product_list = new FloorplanView($this->hall_id);
        $response = $product_list->get_response();

        if(isset($response['cnx_event_view'])){
            $this->arr_view = $response['cnx_event_view'];
        }
        return;
    }

    /**
     * get()
     *
     * Returns the JSON format of the product list
     *
     * @return string
     */
    public function get(){
        return $this->arr_view;
    }
}