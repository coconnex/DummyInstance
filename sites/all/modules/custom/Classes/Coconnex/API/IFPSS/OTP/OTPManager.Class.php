<?

namespace Coconnex\API\IFPSS\OTP;
use Coconnex\Utils\Config\Config;

require_once(dirname(dirname(__FILE__)) . "/OTP/OTPEmail.Class.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Utils/Handlers/GenericHandler.php");
require_once dirname(dirname(dirname(dirname(__FILE__)))). "/Utils/Config/Config.Class.php";

use Coconnex\Utils\Handlers\GenericHandler;
use OTPEmail;
use stdClass;

class OTPManager extends OTPEmail
{

    public $email;
    public $session_id;
    public $expiry_time;
    public $expiry_duration = 120;
    public $expiry_duration_buffer = 10;
    public $otp_code;
    public $uid;
    public $enter_otp;

    public function __construct($uid=0,$data=null)
    {
        parent::__construct();
        $this->uid = $uid;
        $this->parse_data($data);
    }

    protected function parse_data($data){
        $data = json_decode(($data===null)? file_get_contents('php://input') :$data);
        if($data){
            if($data->email){
                $this->email = $data->email;
            }
            if($data->enter_otp){
                $this->enter_otp = $data->enter_otp;
            }
        }
    }

    public function generate(){
        $otp = GenericHandler::getRandomNumber(4);
        $time = time();
        $this->expiry_time = $time+$this->expiry_duration+$this->expiry_duration_buffer;
        $this->otp_code = $otp;
        $this->session_id = session_id();
        $OTP = new stdClass();
        $OTP->email = $this->email;
        $OTP->session_id = $this->session_id;
        $OTP->expiry_time = $this->expiry_time;
        $OTP->expiry_duration = $this->expiry_duration;
        $OTP->otp_code = $this->otp_code;

        $_SESSION['OTP'] = serialize($OTP);

        $this->send_mail();
    }

    public function verify(){

        $session_data = unserialize($_SESSION['OTP']);
        $session_id = session_id();

        $exhib_data = unserialize($_SESSION['sf_exhibitor']);
        $payload = array(
            'company_name'=>$exhib_data->company_name,
            'vat_no'=>$exhib_data->vat_no,
        );
        $time = time();

        if($time < $session_data->expiry_time && $session_data->session_id == $session_id){
            if($session_data->email == $this->email && $session_data->otp_code == $this->enter_otp){
                $response['status'] = 1;
                $response['payload'] = $payload;
                $response['message'] = 'OTP validated successfully.';
            } else {
                $response['status'] = 0;
                $response['message'] = 'OTP did not match.';
            }
        }else {
            $response['status'] = 0;
            $response['message'] = 'OTP expired! Please resend OTP and try again.';
        }
        echo json_encode($response);
        exit;
    }
    public function send_mail()
    {
        if($this->email){
            $this->setEmailType('OTP');
            $obj_config = new Config("d6");
            $exhib_data = unserialize($_SESSION['sf_exhibitor']);
            $user_name = $exhib_data->first_name.' '.$exhib_data->last_name;
            $tokens = array(
                'LOGOIMAGE' => $obj_config->baseurl()."/".$obj_config->getLogoPath(),
                'OTP_CODE' => $this->otp_code,
                'CLARIONEMAIL' => 'topdrawerexhibitors@clarionevents.com',
                'NAME' => $user_name
            );

            $this->applyTokens($tokens);
            $this->setTo($this->email);
            $result = $this->send();

            $otp_data = unserialize($_SESSION['OTP']);
            $payload = array(
                'company_name'=>GenericHandler::obfuscate($exhib_data->company_name),
                'vat_no'=>GenericHandler::obfuscate($exhib_data->vat_no),
                'expiry_time'=> $otp_data->expiry_time,
                'expiry_duration'=> $otp_data->expiry_duration
            );
            if($result){
                $response['status'] = 1;
                $response['payload'] = $payload;
                $response['message'] = 'OTP sent successfully.';
            } else {
                $response['status'] = 0;
                $response['message'] = 'OTP not sent.';
            }
            echo json_encode($response);
            exit;
        }

    }


}
