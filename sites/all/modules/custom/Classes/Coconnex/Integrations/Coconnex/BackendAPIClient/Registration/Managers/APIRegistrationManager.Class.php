<?php

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\Registration\Managers;

require_once(dirname(dirname(__FILE__)) . "/ExhibitorGetData.Class.php");
require_once(dirname(dirname(__FILE__)) . "/ExhibitorSetData.Class.php");
require_once(dirname(dirname(__FILE__)) . "/ExhibitorCheckData.Class.php");
require_once(dirname(dirname(__FILE__)) . "/ExhibitorPreRegister.Class.php");


use Coconnex\Integrations\Coconnex\BackendAPIClient\Registration\ExhibitorCheckData;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Registration\ExhibitorGetData;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Registration\ExhibitorPreRegister;
use Coconnex\Integrations\Coconnex\BackendAPIClient\Registration\ExhibitorSetData;


class APIRegistrationManager
{
    public $exhibitor;
    public $response;
    public $checkedit;
    public $is_lead;

    public function __construct($exhibitor = 0)
    {
        $this->exhibitor = $exhibitor;
        $this->process();
    }

            
   
    protected function process()
    {

        if (is_numeric($this->exhibitor) && $this->exhibitor > 0) {
            $exhib_data = new ExhibitorGetData($this->exhibitor);
            // debug($this->exhibitor,1);
            $response = $exhib_data->get_response();
            // debug($response,1);
            if (isset($response['exhibitor_data']) ) {
                $this->exhibitor = $response['exhibitor_data'];
                $this->checkedit = $response['checkedit'];
                $this->is_lead = $response['is_lead'];
            }
            return;
        } else {

            $result = unserialize($this->exhibitor);
            if ($result) {
                if (isset($result->sf_con_account_id) && $result->sf_con_account_id != '') {
                    //   debug($result,1);
                    $exhib_data = new ExhibitorPreRegister($result->sf_con_account_id, $result);
                    $response = $exhib_data->set_response();
                    if (isset($response)) {
                        $this->exhibitor = $response->exhibitor;
                    }
                    return $response;
                } else {
                    // debug( $this->exhibitor ,1);
                    $exhib_data = new ExhibitorSetData(0, $this->exhibitor);
                    $response = $exhib_data->set_response();
                    if (isset($response)) {
                        $this->exhibitor = $response->exhibitor_id;
                    }
                    return $this->exhibitor;
                }
            } else {
                $exhibitor_email = $this->exhibitor;
                // debug( $exhibitor_email,1);
                $exhib_data = new ExhibitorCheckData($exhibitor_email);
                $response = $exhib_data->check_response();
                if (isset($response['sf_exhibitor_details'])) {
                    $this->exhibitor = $response['sf_exhibitor_details'];
                }
                return $response;
            }
        }
    }
}
