<?

namespace Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions;

// require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/Connector/APIConnecter.Class.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/BackendAPIClient/StandTransactions/Models/ResponseModels/BookResponseModel.Class.php");

use Coconnex\Integrations\Coconnex\BackendAPIClient\Connector\APIConnector;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\RequestModels\BookRequestModel;
use Coconnex\Integrations\Coconnex\BackendAPIClient\StandTransactions\Models\ResponseModels\BookResponseModel;

class Book extends APIConnector{

    public function __construct()
    {
        parent::__construct('transactions');
    }

    public function get_response($endpoint,$req_data){
        $book_response_status = false;

        if($req_data instanceof BookRequestModel){
            if($this->cnx_api_key != ""){
                $method = 'POST';
                $data = json_encode($req_data);
                $response = $this->send($method,$endpoint,$data);

                if($response['response_status']){
                    $book_response_status = true;
                    $book_response_data = $this->set_response_model($response['response_data']);

                    $book_response = json_encode(array('status' => $book_response_status, 'data' => $book_response_data));
                }else{
                    $error_message = $response['message'];
                    $book_response = json_encode(array('status' => $book_response_status, 'msg' => $error_message));
                }
            }else{
                $error_message = 'Sorry! CNX API KEY not found.';
                $book_response = json_encode(array('status' => $book_response_status, 'msg' => $error_message));
            }
        }
        return $book_response;
    }

    protected function set_response_model($response){
        $book_res_model = new BookResponseModel();
        if($book_res_model){
            $book_res_model->message = $response['message'];
        }
        return $book_res_model;
    }

}