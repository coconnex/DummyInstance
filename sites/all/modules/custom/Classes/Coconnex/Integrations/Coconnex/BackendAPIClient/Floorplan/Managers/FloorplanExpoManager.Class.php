<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\Managers;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\FloorplanExpo;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\FloorplanSkeleton;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\Managers\FloorplanStatusManager;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Floorplan\Collection\StandTypes;
use Coconnex\Utils\Config\Config;
use Coconnex\API\IFPSS\WaitingList\Collection\WaitingList;

require_once(dirname(dirname(__FILE__)) . "/FloorplanSkeleton.Class.php");
require_once(dirname(dirname(__FILE__)) . "/FloorplanExpo.Class.php");
require_once(dirname(dirname(__FILE__)) . "/Collection/StandTypes.Class.php");
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/Utils/Config/Config.Class.php");
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/API/IFPSS/WaitingList/Collection/WaitingList.Class.php");
require_once(dirname(__FILE__) . "/FloorplanStatusManager.Class.php");

class FloorplanExpoManager
{
    protected $uid;
    protected $hall_id;
    protected $xml_expo;
    protected $xml_event;
    protected $arr_stands;
    protected $arr_self_stands;
    protected $arr_statuses;
    protected $arr_display_statuses;
    protected $arr_myzone;

    protected $vbScale;
    protected $stand_shape_node;
    protected $stand_event_node;
    protected $stand_area_node;
    protected $stand_dims_node;
    protected $stand_name_node;
    protected $stand_num_node;
    protected $stand_segments_node;

    protected $display_stand_dims;
    protected $display_stand_area;
    protected $display_stand_name;

    protected $waitinglist_pattern;
    protected $waitinglist_pattern_colour;
    protected $waitinglist_pattern_stroke_width;
    protected $waitinglist_pattern_density;
    protected $waitinglist;

    public $response;

    public function __construct($hall_id)
    {
        $this->hall_id = $hall_id;
        $this->arr_stands = array();
        $this->arr_self_stands = array();

        $obj_statuses = new FloorplanStatusManager($hall_id);
        $this->arr_statuses = $obj_statuses->get();
        $this->arr_display_statuses = $obj_statuses->get_display_statuses();
        // debug($this->arr_display_statuses,1);
        unset($obj_statuses);

        $this->arr_myzone = array();

        $this->vbScale = 1;
        $this->xml_expo = new \DOMDocument('1.0');
        $this->xml_event = new \DOMDocument('1.0');

        $this->stand_shape_node = null;
        $this->stand_event_node = null;
        $this->stand_area_node = null;
        $this->stand_dims_node = null;
        $this->stand_name_node = null;
        $this->stand_num_node = null;
        $this->stand_segments_node = null;

        $obj_config = new Config("d6");
        $this->display_stand_dims = $obj_config::getvar("DISPLAY_STAND_DIMS");
        $this->display_stand_area = $obj_config::getvar("DISPLAY_STAND_AREA");
        $this->display_stand_name = $obj_config::getvar("DISPLAY_STAND_NAME");
        $this->uid = $obj_config::uid();

        $this->waitinglist_pattern = $obj_config::getvar("WAITINGLIST_PATTERN");
        $this->waitinglist_pattern_colour = $obj_config::getvar("WAITINGLIST_PATTERN_COLOUR");
        $this->waitinglist_pattern_stroke_width = $obj_config::getvar("PATTERN_STROKE_WIDTH");
        $this->waitinglist_pattern_density = $obj_config::getvar("PATTERN_DENSITY");
        $this->waitinglist = array();


        $this->populateWaitingList();
        // $this->process();
    }

    public function set_myzone($arr_myzone){
        $this->arr_myzone = $arr_myzone;
        return;
    }

    public function set_self_stands($arr_self_stands){
        $this->arr_self_stands = $arr_self_stands;
        return;
    }

    public function get_display_statuses(){
        return $this->arr_display_statuses;
    }

    protected function populateWaitingList(){
        $wl = new WaitingList($this->uid);
        $wl->get_list();
        foreach($wl->waiting_list as $item){
            $this->waitinglist[] = $item->stand_ref_id;
        }
        unset($wl);
        return;
    }
    /**
     * process()
     *
     * Processes the calls required to get the skeleton feed and stand feed. As a result populates the SVG DOM object for expo and the the SVG DOM object for event.
     * @return void
     */
    public function process(){

        $fp_skeleton = new FloorplanSkeleton($this->hall_id);
        $response = $fp_skeleton->get_response();
        // debug($response,1);
        if(is_array($response)){
            debug($response['message']);
        }else{
            if($this->xml_expo->loadXML($response)){
                $this->xml_event->loadXML($response);
                $this->setVBscale();
                $this->addWaitingListPatternDef();


                $fp_expo = new FloorplanExpo($this->hall_id);
                $response = $fp_expo->get_response();
                // debug($response,1);
                if(isset($response['cnx_event_hall_stands'])){
                    $this->arr_stands = $response['cnx_event_hall_stands'];

                    $this->setup_expo_layers();
                    $this->setup_expo_event_layers();
                    $this->setup_stand_svg_elements();
                }
            }
        }
        return;
    }

    protected function addWaitingListPatternDef(){
        $defNode = null;
        $xpathSVG = new \DOMXPath($this->xml_event);
        $svg = $this->xml_event->documentElement;
        $defQuery = "//*[name() = 'defs']";
        $defList = $xpathSVG->query($defQuery);
        if($defList->length > 0){
            $defNode = $defList->item(0);
        } else {
            $defNode = $this->xml_event->createElement('defs');
            $this->xml_event->documentElement->appendChild($defNode);
        }
        if($this->waitinglist_pattern !== ""){
            $tempDom = new \DOMDocument;
            if($tempDom->loadXML($this->waitinglist_pattern)){
                $patHeight = $this->waitinglist_pattern_density*$this->vbScale;
                $patWidth = $this->waitinglist_pattern_density*$this->vbScale;
                $tempDom->documentElement->setAttribute("height",$patHeight);
                $tempDom->documentElement->setAttribute("width",$patWidth);

                $xpath = new \DOMXPath($tempDom);
                $xQuery = "//*[name()= 'pattern']/*";
                $patternNode = $xpath->query($xQuery);
                if($patternNode->length > 0){
                    foreach($patternNode as $patternElement){

                        if($patternElement->hasAttribute('stroke')){
                            $patternElement->setAttribute('stroke-width',$this->waitinglist_pattern_stroke_width);
                            $patternElement->setAttribute('stroke',$this->waitinglist_pattern_colour);
                        }
                    }
                }
                $defNode->appendChild($defNode->ownerDocument->importNode($tempDom->documentElement,true));
            }
        }
        return;
    }

    protected function setVBscale(){
        $root_elem = $this->xml_expo->documentElement;
        if($root_elem->hasAttribute("viewBox")){
            $vBoxArray = preg_split("/[\s]+/",$root_elem->getAttribute("viewBox"));
            // debug($root_elem->getAttribute("viewBox"),1);
            if(sizeof($vBoxArray)==4){
				$vbArray = array('vpx'=>0,'vpy'=>0,'vpw'=>1200,'vph'=>900,'vbx'=>$vBoxArray[0],'vby'=>$vBoxArray[1],'vbw'=>$vBoxArray[2],'vbh'=>$vBoxArray[3]);
				$wScale = $vbArray['vbw']/$vbArray['vpw'];
                $hScale = $vbArray['vbh']/$vbArray['vph'];
                $this->vbScale = max($wScale,$hScale);
			}
        }
        return;
    }

    /**
     * getExpoDOM()
     *
     * Returns the SVG Expo DOM feed as string
     *
     * @return string
     */
    public function getExpoDOM(){
        return $this->xml_expo->saveXML();
    }

    /**
     * getExpoEventDOM()
     *
     * Returns the SVG Expo DOM feed for event as string. The click event for each stand is handled from the floorplan.js
     *
     * @return string
     */
    public function getExpoEventDOM(){
        return $this->xml_event->saveXML();
    }

    /**
     * setup_stand_svg_elements()
     *
     * Populates the stand layers (by looping the stand feed) with the SVG objects namely stand shape, stand number, stand dimensions, stand name, stand area and display segments.
     * Also, populates the standEvent layer for the stand event.
     *
     * @return void
     */
    protected function setup_stand_svg_elements(){
        $obj_stand_type = new StandTypes();
        $arr_stand_types = $obj_stand_type->get();

        foreach($this->arr_stands as $idx => $stand){

            if($this->stand_shape_node){
                if(isset($stand['STAND_SHAPE'])){
                    if(isset($stand['STAND_SHAPE']['SVG_ELEMENT'])){
                        $this->append_shape_svg_node($stand, $this->stand_shape_node, $arr_stand_types);
                        $this->append_shape_event_svg_node($stand, $this->stand_event_node, $arr_stand_types);
                    }
                }
            }

            if($this->stand_area_node){
                if(isset($arr_stand_types[$stand['STAND_TYPE']['KEY']])){
                    if($arr_stand_types[$stand['STAND_TYPE']['KEY']]->type_key != 'STG'){
                        if(isset($stand['STAND_AREA'])){
                            if(isset($stand['STAND_AREA']['SVG_ELEMENT'])){
                                $this->append_svg_node($stand['STAND_AREA']['SVG_ELEMENT'], $this->stand_area_node);
                            }
                        }
                    }
                }
            }

            if($this->stand_name_node){
                if($this->display_stand_name == "1"){
                    if(isset($stand['STAND_NAME'])){
                        if(isset($stand['STAND_NAME']['SVG_ELEMENT'])){
                            $this->append_svg_node($stand['STAND_NAME']['SVG_ELEMENT'], $this->stand_name_node);
                        }
                    }
                }else{
                    if(isset($stand['STAND_TYPE']['KEY'])) {
                        if(isset($arr_stand_types[$stand['STAND_TYPE']['KEY']])){
                            if($arr_stand_types[$stand['STAND_TYPE']['KEY']]->type_key == 'FEA'){
                                if(isset($stand['STAND_NAME'])){
                                    if(isset($stand['STAND_NAME']['SVG_ELEMENT'])){
                                        $this->append_svg_node($stand['STAND_NAME']['SVG_ELEMENT'], $this->stand_name_node);
                                    }
                                }
                            }
                            if($arr_stand_types[$stand['STAND_TYPE']['KEY']]->type_key == 'STG'){
                                if(isset($stand['STAND_NAME'])){
                                    if(isset($stand['STAND_NAME']['SVG_ELEMENT'])){
                                        $this->append_svg_node($stand['STAND_NAME']['SVG_ELEMENT'], $this->stand_name_node);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if($this->stand_num_node){
                if(isset($arr_stand_types[$stand['STAND_TYPE']['KEY']])){
                    if($arr_stand_types[$stand['STAND_TYPE']['KEY']]->type_key != 'STG'){
                        if(isset($stand['STAND_NUMBER'])){
                            if(isset($stand['STAND_NUMBER']['SVG_ELEMENT'])){
                                $this->append_svg_node($stand['STAND_NUMBER']['SVG_ELEMENT'], $this->stand_num_node);
                            }
                        }
                    }
                }
            }

            if($this->stand_dims_node){
                if(isset($stand['STAND_DIMENSIONS'])){
                    if(isset($stand['STAND_DIMENSIONS']['SVG_ELEMENT'])){
                        foreach($stand['STAND_DIMENSIONS']['SVG_ELEMENT'] as $idx => $dim_element){
                            $this->append_svg_node($dim_element, $this->stand_dims_node);
                        }
                    }
                }
            }

            if($this->stand_segments_node){
                if(isset($stand['STAND_SEGMENTS'])){
                    if(isset($stand['STAND_SEGMENTS']['SVG_ELEMENT'])){
                        $this->append_svg_node("<g>".$stand['STAND_SEGMENTS']['SVG_ELEMENT']."</g>", $this->stand_segments_node);
                    }
                }
            }
        }
    }

    /**
     * append_svg_node()
     *
     * Appends the svg element which is in string to the group node
     *
     * @param  string $svg_element SVG element as string
     * @param  objectref $group_node Reference of the group layer node
     * @return void
     */
    protected function append_svg_node($svg_element, &$group_node){
        $tempDOM = new \DOMDocument("1.0","UTF-8");
        if($tempDOM->loadXML($svg_element)){
            $svgNode = $tempDOM->childNodes->item(0);
            if($svgNode->hasAttribute("stroke")) $svgNode->removeAttribute("stroke");
            $group_node->appendChild($group_node->ownerDocument->importNode($svgNode,true));
        }
        return;
    }

    /**
     * append_shape_svg_node()
     *
     *  Appends the stand shape svg element which is in string to the group node by adding necessary parameters as attributes.
     *
     * @param  array $stand_item Sub-array of the stand
     * @param  objectref $group_node Reference of the group layer node
     * @param  array $arr_stand_types Array of the stand types
     * @return void
     */
    protected function append_shape_svg_node($stand_item,&$group_node, $arr_stand_types){
        $tempDOM = new \DOMDocument("1.0","UTF-8");
        if($tempDOM->loadXML($stand_item['STAND_SHAPE']['SVG_ELEMENT'])){

            $stand_type = 'STD';
            $stand_id = 0;
            $svgNode = $tempDOM->childNodes->item(0);

            if(isset($stand_item['STAND_AREA']['VALUE'])) $svgNode->setAttribute("ar",$stand_item['STAND_AREA']['VALUE']);
            if(isset($stand_item['STAND_HALL']['URN'])) $svgNode->setAttribute("hall",base64_decode($stand_item['STAND_HALL']['URN']));
            if(isset($stand_item['STAND_NAME']['VALUE'])) $svgNode->setAttribute("ex",$stand_item['STAND_NAME']['VALUE']);
            if(isset($stand_item['STAND_NUMBER']['VALUE'])) $svgNode->setAttribute("no",$stand_item['STAND_NUMBER']['VALUE']);
            if(isset($stand_item['STAND_URN'])) {
                $stand_id = base64_decode($stand_item['STAND_URN']);
                $svgNode->setAttribute("id",$stand_id);
            }
            if(isset($this->arr_statuses[$stand_item['STAND_STATUS']])){
                $svgNode->setAttribute("fill",$this->arr_statuses[$stand_item['STAND_STATUS']]->back_color);
                $svgNode->setAttribute("sts",$this->arr_statuses[$stand_item['STAND_STATUS']]->status_key);
            }
            if(isset($stand_item['STAND_TYPE']['KEY'])) {
                if(isset($arr_stand_types[$stand_item['STAND_TYPE']['KEY']])){
                    if($arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key != 'STD'){
                        // $svgNode->setAttribute("fill",$arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_color);
                        $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                        $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                        $stand_type = $arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key;
                    }
                    if($arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key == 'FEA'){
                        $svgNode->setAttribute("fill",$arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_color);
                        $stand_type = 'FEA';
                    }
                    if($arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key == 'STG'){
                        $svgNode->setAttribute("fill",$arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_color);
                        $stand_type = 'STG';
                    }
                    $svgNode->setAttribute("type",$arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key);
                }
            }

            if(isset($stand_item['PRICECAT']['KEY'])){
                if($stand_item['PRICECAT']['KEY'] === "PRM"  && $stand_item['STAND_STATUS'] === 'AVA'){
                    $svgNode->setAttribute("fill",$this->arr_statuses['AVA']->back_color);
                }
            }

            if(isset($stand_item['STAND_ISSELFSERVICE'])){
                if($stand_item['STAND_ISSELFSERVICE'] == 0 && $stand_item['STAND_TYPE']['KEY'] == "STD"){
                    // $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                    $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                    if($stand_item['STAND_STATUS'] === 'AVA'){
                        $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                    }
                }
            }

            if(isset($this->arr_myzone['URN']) && isset($stand_item['STAND_ZONE']['URN'])){
                if($this->arr_myzone['URN'] > 0 && base64_decode($stand_item['STAND_ZONE']['URN']) > 0 && $stand_type == 'STD'){
                    if($this->arr_myzone['URN'] != base64_decode($stand_item['STAND_ZONE']['URN']) && $stand_item['STAND_STATUS'] === 'AVA'){
                        $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                        $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                    }
                }
                if($this->arr_myzone['URN'] == 0 && $stand_type == 'STD'){
                    $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                    $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                }
            }

            if(count($this->arr_self_stands) > 0){
                if(isset($this->arr_self_stands[$stand_id])){
                    if($stand_item['STAND_STATUS'] == 'CNT' || $stand_item['STAND_STATUS'] == 'CNP'){
                        $svgNode->setAttribute("fill",$this->arr_display_statuses['CNT']->back_color);
                        $svgNode->setAttribute("sts",$this->arr_display_statuses['CNT']->status_key);
                    }else{
                        $svgNode->setAttribute("fill",$this->arr_display_statuses[$stand_item['STAND_STATUS']]->back_color);
                        $svgNode->setAttribute("sts",$this->arr_display_statuses[$stand_item['STAND_STATUS']]->status_key);
                    }
                }
            }

            if(isset($stand_item['STAND_DESCID'])){
                if($stand_item['STAND_DESCID'] > 0){
                    if(isset($this->arr_self_stands[$stand_id])){
                        if($this->arr_self_stands[$stand_id]['CURR'] == 'RESERVED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_SUBMITTED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_ACCEPTED'){
                            $svgNode->setAttribute("fill",$this->arr_display_statuses['RVD']->back_color);
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['RVD']->status_key);
                        }
                        if($this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_COMPLETED'){
                            $svgNode->setAttribute("fill",$this->arr_display_statuses['CNT']->back_color);
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['CNT']->status_key);
                        }
                        if(($this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_CANCELLATION_REQUESTED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CANCELLATION_INITIATED') && $this->arr_self_stands[$stand_id]['PREV'] == 'CONTRACT_COMPLETED'){
                            $svgNode->setAttribute("fill",$this->arr_display_statuses['CNT']->back_color);
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['CNT']->status_key);
                        }
                        if(($this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_CANCELLATION_REQUESTED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CANCELLATION_INITIATED') && ($this->arr_self_stands[$stand_id]['PREV'] == 'CONTRACT_ACCEPTED' || $this->arr_self_stands[$stand_id]['PREV'] == 'CONTRACT_SUBMITTED')){
                            $svgNode->setAttribute("fill",$this->arr_display_statuses['RVD']->back_color);
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['RVD']->status_key);
                        }
                    }else{
                        $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                        $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                    }
                }
            }

            $group_node->appendChild($group_node->ownerDocument->importNode($svgNode,true));
        }
        return;
    }

    /**
     * append_shape_event_svg_node()
     *
     * Appends the stand shape svg element which is in string to the group node standEvent for event capturing. Necessary parameters as attributes as also added.
     *
     * @param  array $stand_item Sub-array of the stand
     * @param  objectref $group_node Reference of the group layer node standEvent
     * @param  array $arr_stand_types Array of the stand types
     * @return void
     */
    protected function append_shape_event_svg_node($stand_item,&$group_node, $arr_stand_types){
        $tempDOM = new \DOMDocument("1.0","UTF-8");
        if($tempDOM->loadXML($stand_item['STAND_SHAPE']['SVG_ELEMENT'])){

            $stand_type = 'STD';
            $stand_id = 0;
            $svgNode = $tempDOM->childNodes->item(0);

            if(isset($stand_item['STAND_AREA']['VALUE'])) $svgNode->setAttribute("ar",$stand_item['STAND_AREA']['VALUE']);
            if(isset($stand_item['STAND_HALL']['URN'])) $svgNode->setAttribute("hall",base64_decode($stand_item['STAND_HALL']['URN']));
            if(isset($stand_item['STAND_NAME']['VALUE'])) $svgNode->setAttribute("ex",$stand_item['STAND_NAME']['VALUE']);
            if(isset($stand_item['STAND_NUMBER']['VALUE'])) $svgNode->setAttribute("no",$stand_item['STAND_NUMBER']['VALUE']);
            if(isset($stand_item['STAND_URN'])) {
                $stand_id = base64_decode($stand_item['STAND_URN']);
                $svgNode->setAttribute("id",$stand_id);
            }
            if(isset($stand_item['STAND_OPENSIDES']['COUNT'])) $svgNode->setAttribute("os",$stand_item['STAND_OPENSIDES']['COUNT']);
            if(isset($stand_item['STAND_DIMENSIONS']['CSV'])) $svgNode->setAttribute("dm",$stand_item['STAND_DIMENSIONS']['CSV']);

            $svgNode->setAttribute("ht",0);
            if(isset($this->arr_statuses[$stand_item['STAND_STATUS']])){
                // $svgNode->setAttribute("fill",$this->arr_statuses[$stand_item['STAND_STATUS']]->back_color);
                $svgNode->setAttribute("sts",$this->arr_statuses[$stand_item['STAND_STATUS']]->status_key);
            }

            if(isset($stand_item['STAND_ISSELFSERVICE'])){
                if($stand_item['STAND_ISSELFSERVICE'] == 0){
                    // $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                    $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                }
                if($stand_item['STAND_ISSELFSERVICE'] == 1){
                    if(isset($stand_item['PRICECAT']['KEY'])){
                        $svgNode->setAttribute("pcat",$stand_item['PRICECAT']['KEY']);
                    }
                }
            }
            if(isset($stand_item['STAND_TYPE']['KEY'])) {
                if(isset($arr_stand_types[$stand_item['STAND_TYPE']['KEY']])){
                    if($arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key != 'STD'){
                        $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                        $stand_type = $arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key;
                    }
                    if($arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key == 'FEA'){
                        $svgNode->setAttribute("fill",$arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_color);
                        $stand_type = 'FEA';
                    }
                    $svgNode->setAttribute("type",$arr_stand_types[$stand_item['STAND_TYPE']['KEY']]->type_key);
                }
            }
            if(isset($stand_item['STAND_ZONE'])) {
                if(isset($stand_item['STAND_ZONE']['URN'])) {
                    if($stand_item['STAND_ZONE']['URN'] != ""){
                        $svgNode->setAttribute("z",base64_decode($stand_item['STAND_ZONE']['URN']));
                    }
                }
                if(isset($stand_item['STAND_ZONE']['NAME'])) {
                    if($stand_item['STAND_ZONE']['NAME'] != ""){
                        $svgNode->setAttribute("zn",$stand_item['STAND_ZONE']['NAME']);
                    }
                }
                if(isset($stand_item['STAND_ZONE']['COLOUR'])) {
                    if($stand_item['STAND_ZONE']['COLOUR'] != ""){
                        $svgNode->setAttribute("zc",$stand_item['STAND_ZONE']['COLOUR']);
                    }
                }
            }

            if(isset($this->arr_myzone['URN']) && isset($stand_item['STAND_ZONE']['URN'])){
                if($this->arr_myzone['URN'] > 0 && base64_decode($stand_item['STAND_ZONE']['URN']) > 0 && $stand_type == 'STD'){
                    if($this->arr_myzone['URN'] != base64_decode($stand_item['STAND_ZONE']['URN']) && $stand_item['STAND_STATUS'] === 'AVA'){
                        $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                        if($svgNode->hasAttribute("pcat")){
                            $svgNode->removeAttribute("pcat");
                        }
                    }
                }
                if($this->arr_myzone['URN'] == 0 && $stand_type == 'STD'){
                    $svgNode->setAttribute("fill",$this->arr_statuses['BLK']->back_color);
                    $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                }
            }

            if(count($this->arr_self_stands) > 0){
                if(isset($this->arr_self_stands[$stand_id])){
                    if($stand_item['STAND_STATUS'] == 'CNT' || $stand_item['STAND_STATUS'] == 'CNP'){
                        $svgNode->setAttribute("sts",$this->arr_display_statuses['CNT']->status_key);
                    }else{
                        $svgNode->setAttribute("sts",$this->arr_display_statuses[$stand_item['STAND_STATUS']]->status_key);
                    }
                }
            }

            if(isset($stand_item['STAND_DESCID'])){
                if($stand_item['STAND_DESCID'] > 0){
                    if(isset($this->arr_self_stands[$stand_id])){
                        if($this->arr_self_stands[$stand_id]['CURR'] == 'RESERVED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_SUBMITTED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_ACCEPTED'){
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['RVD']->status_key);
                        }
                        if($this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_COMPLETED'){
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['CNT']->status_key);
                        }
                        if(($this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_CANCELLATION_REQUESTED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CANCELLATION_INITIATED') && $this->arr_self_stands[$stand_id]['PREV'] == 'CONTRACT_COMPLETED'){
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['CNT']->status_key);
                        }
                        if(($this->arr_self_stands[$stand_id]['CURR'] == 'CONTRACT_CANCELLATION_REQUESTED' || $this->arr_self_stands[$stand_id]['CURR'] == 'CANCELLATION_INITIATED') && ($this->arr_self_stands[$stand_id]['PREV'] == 'CONTRACT_ACCEPTED' || $this->arr_self_stands[$stand_id]['PREV'] == 'CONTRACT_SUBMITTED')){
                            $svgNode->setAttribute("sts",$this->arr_display_statuses['RVD']->status_key);
                        }
                    }else{
                        $svgNode->setAttribute("sts",$this->arr_statuses['BLK']->status_key);
                    }
                }
            }

            $svgNode->setAttribute("style","cursor:pointer;");
            if(in_array($stand_id,$this->waitinglist)){
                $svgNode->setAttribute("fill","url(#criscross)");
                $svgNode->setAttribute("opacity","0.5");
                $svgNode->setAttribute("sts","WAIT");
            }else{
                $svgNode->setAttribute("fill","#FFFFFF");
                $svgNode->setAttribute("opacity","0.01");
            }

            $group_node->appendChild($group_node->ownerDocument->importNode($svgNode,true));
        }
        return;
    }


    /**
     * setup_expo_layers()
     *
     * Group layers are appended to the expo skeleton fetched from the backend.
     *
     * @return void
     */
    protected function setup_expo_layers(){
        $draftnode = $this->get_draft_node();

        $stand_shape_node = $this->xml_expo->createElement("g");
        $stand_shape_node->setAttribute("id", "STAND_SHAPE");
        $draftnode->appendChild($stand_shape_node);
        $this->stand_shape_node = $stand_shape_node;

        if($this->display_stand_area == "1"){
            $stand_area_node = $this->xml_expo->createElement("g");
            $stand_area_node->setAttribute("id", "STAND_AREA");
            $draftnode->appendChild($stand_area_node);
            $this->stand_area_node = $stand_area_node;
        }

        if($this->display_stand_dims == "1"){
            $stand_dims_node = $this->xml_expo->createElement("g");
            $stand_dims_node->setAttribute("id", "STAND_DIMENSIONS");
            $draftnode->appendChild($stand_dims_node);
            $this->stand_dims_node = $stand_dims_node;
        }

        $stand_name_node = $this->xml_expo->createElement("g");
        $stand_name_node->setAttribute("id", "STAND_NAME");
        $draftnode->appendChild($stand_name_node);
        $this->stand_name_node = $stand_name_node;

        $stand_num_node = $this->xml_expo->createElement("g");
        $stand_num_node->setAttribute("id", "STAND_NUMBER");
        $draftnode->appendChild($stand_num_node);
        $this->stand_num_node = $stand_num_node;

        $stand_segments_node = $this->xml_expo->createElement("g");
        $stand_segments_node->setAttribute("id", "STAND_SEGMENTS");
        $draftnode->appendChild($stand_segments_node);
        $this->stand_segments_node = $stand_segments_node;
        return;
    }

    /**
     * setup_expo_event_layers()
     *
     * Group layer standEvent is appended to the expo event skeleton fetched from the backend.
     *
     * @return void
     */
    protected function setup_expo_event_layers(){
        $draftnode = $this->get_event_draft_node();

        $stand_shape_node = $this->xml_event->createElement("g");
        $stand_shape_node->setAttribute("id", "searchlayer");
        $draftnode->appendChild($stand_shape_node);

        $stand_shape_node = $this->xml_event->createElement("g");
        $stand_shape_node->setAttribute("id", "standEvent");
        $draftnode->appendChild($stand_shape_node);
        $this->stand_event_node = $stand_shape_node;

        return;
    }

    /**
     * get_draft_node()
     *
     * Returns the draft node from the expo skeleton
     *
     * @return DOMElement
     */
    protected function get_draft_node(){
        // Assigning the root node of the main xml_expo
        $draftnode = $this->xml_expo->documentElement;

        // If the draft node is present then change assignment of the draftnode variable to the draft node
        $xpathMainSVG = new \DOMXPath($this->xml_expo);
        $layerQuery = "//*[name()='g' and @id='draft']";
        $layerList = $xpathMainSVG->query($layerQuery);
        if($layerList->length > 0){
            $draftnode = $layerList->item(0);
        }

        return $draftnode;
    }

    /**
     * get_event_draft_node()
     *
     * Returns the draft node from the expo event skeleton
     *
     * @return DOMElement
     */
    protected function get_event_draft_node(){
        // Assigning the root node of the main xml_event
        $draftnode = $this->xml_event->documentElement;

        // If the draft node is present then change assignment of the draftnode variable to the draft node
        $xpathMainSVG = new \DOMXPath($this->xml_event);
        $layerQuery = "//*[name()='g' and @id='draft']";
        $layerList = $xpathMainSVG->query($layerQuery);
        if($layerList->length > 0){
            $draftnode = $layerList->item(0);
        }

        return $draftnode;
    }
}