<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Masters\Managers;

require_once(dirname(dirname(__FILE__)) . "/SectorsGetData.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Masters\SectorsGetData;

class APIMastersManagers
{
    public $type;
    public $id;
    public $response;
    public $data;

    public function __construct($type = null, $id=null)
    {
        $this->type = $type;
        $this->process();
    }

    protected function process()
    {

        if ($this->type != null  && $this->type == 'sectors') {
            if(is_numeric($this->id) && $this->id > 0 ){
                $cat_data = new SectorsGetData($this->id);
                $response = $cat_data->get_response();
                // debug( $response,1);
                if (isset($response['sector_data'])) {
                    $this->data = $response['sector_data'];
                }
                return  $this->data;
            }else{
                $cat_data = new SectorsGetData();
                $response = $cat_data->get_response();
                // debug( $response,1);
                if (isset($response['sector_data'])) {
                    $this->data = $response['sector_data'];
                }
                return  $this->data;
            }
        } 
    }
}
