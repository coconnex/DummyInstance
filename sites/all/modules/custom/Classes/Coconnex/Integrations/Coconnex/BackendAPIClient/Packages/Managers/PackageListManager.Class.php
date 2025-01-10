<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Packages\Managers;

use Coconnex\Integrations\Coconnex\BackendAPIClient\Packages\PackageList;

require_once(dirname(dirname(__FILE__)) . "/PackageList.Class.php");

class PackageListManager
{
    protected $arr_products;
    protected $customer_nid;
    public $response;

    public function __construct($customer_nid)
    {
        $this->arr_products = array();
        $this->customer_nid = $customer_nid;
        $this->process();
    }


    /**
     * process()
     *
     * Processes the call required to fetch the list of products with their pricing.
     * @return void
     */
    protected function process()
    {

        $product_list = new PackageList($this->customer_nid);
        $response = $product_list->get_response();

        if (isset($response['cnx_event_packages'])) {
            // debug($response['cnx_event_packages'],1);
            $this->arr_products = $response['cnx_event_packages'];
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
    public function get()
    {
        return json_encode($this->arr_products);
    }

    public function getZone()
    {
        $zone_array = array(
            "NAME" => "",
            "URN" => 0
        );
        if (isset($this->arr_products['CONFIG'])) {
            if (isset($this->arr_products['CONFIG']['zone_name'])) {
                $zone_array['NAME'] = $this->arr_products['CONFIG']['zone_name'];
            }
            if (isset($this->arr_products['CONFIG']['zone_urn'])) {
                if ($this->arr_products['CONFIG']['zone_urn'] > 0) {
                    $zone_array['URN'] = $this->arr_products['CONFIG']['zone_urn'];
                }
            }else{
                $zone_array['URN'] = -1;
            }
        }
        return $zone_array;
    }
    public function get_applicable_packagelist()
    {
        //extract product ids
        $company_product_ids = $this->extract_product_ids($this->arr_products['CONFIG']['company_type_packages']);
        $zone_product_ids = $this->extract_product_ids($this->arr_products['CONFIG']['zone_packages']);
        // Finding the common product IDs
        $common_product_ids = array_intersect($company_product_ids, $zone_product_ids);
        $applicable_package = array();
        if(sizeof($common_product_ids) > 0){
            for ($i = 0; $i < count($this->arr_products['DATA']); $i++) {
                if (in_array($this->arr_products['DATA'][$i]['urn'], $common_product_ids)) {
                    $applicable_package[] = $this->arr_products['DATA'][$i];
                }
            }
        }else{
            for ($i = 0; $i < count($this->arr_products['DATA']); $i++) {
                if ($this->arr_products['DATA'][$i]['package_type'] != 'FEE') {
                    $applicable_package[] = $this->arr_products['DATA'][$i];
                }
            }
        }
        return  $applicable_package;
    }
    public function extract_product_ids($array)
        {
            $product_ids = [];
            foreach ($array as $item) {
                $product_ids[] = $item['product_id'];
            }
            return $product_ids;
        }
}
