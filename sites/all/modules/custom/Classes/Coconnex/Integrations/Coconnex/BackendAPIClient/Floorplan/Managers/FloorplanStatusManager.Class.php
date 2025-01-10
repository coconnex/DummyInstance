<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\Managers;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\FloorplanStatuses;
use Coconnex\DBFactory\Db;

require_once(dirname(dirname(__FILE__)) . "/FloorplanStatuses.Class.php");
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/DBFactory/Db.Class.php");

class FloorplanStatusManager
{
    protected $arr_statuses;
    protected $arr_display_statuses;
    protected $raw_statuses;
    protected $hall_id;
    public $response;

    public function __construct($hall_id)
    {
        $this->arr_statuses = array();
        $this->arr_display_statuses = array();
        $this->raw_statuses = array();
        $this->hall_id = $hall_id;
        $this->process();
    }

    /**
     * process()
     *
     * Processes the call required to fetch the list of statuses.
     * @return void
     */
    protected function process(){

        $status_list = new FloorplanStatuses($this->hall_id);
        $response = $status_list->get_response();

        if(isset($response['cnx_statuses'])){
            $this->raw_statuses = $response['cnx_statuses'];
            foreach($this->raw_statuses as $idx => $status){
                $obj = new \stdClass();
                $obj->description = $status['frontend_status_desc'];
                $obj->back_color = $status['back_colour'];
                $obj->status_key = $status['frontend_status_key'];
                $obj->font_color = $status['font_colour'];
                $obj->backend_key = $status['status_key'];

                $this->arr_statuses[$status['status_key']] = $obj;
                $this->arr_display_statuses[$status['frontend_status_key']] = $obj;
            }
        }
        $db = new Db();
        $sql = "SELECT
                    field_status_description_value description,
                    field_status_key_value status_key,
                    field_status_color_value back_color,
                    field_font_color_value font_color
                FROM content_type_status
                WHERE field_status_is_active_value=1
                ORDER BY field_status_sequence_value;";
        $result = $db->RetrieveRecord($sql);
        if($result){
            foreach($result as $row){
                $obj = new \stdClass();
                $obj->description = $row['description'];
                $obj->back_color = $row['back_color'];
                $obj->status_key = $row['status_key'];
                $obj->font_color = $row['font_color'];
                $obj->backend_key = $row['status_key'];
                $this->arr_display_statuses[$row['status_key']] = $obj;
            }
        }
        // debug($this->arr_statuses,1);
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
        return $this->arr_statuses;
    }

    public function get_display_statuses(){
        return $this->arr_display_statuses;
    }
}