<?
namespace Coconnex\API\Reminders\Managers;

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;
use Coconnex\Utils\Config\Config;

class TransactionReminderManager extends Db{
    public $config;
    public $schedule_id;
    public $obj_users = array();


    public function __construct($config,$schedule_id)
    {
        if($config != "" && $schedule_id > 0){
            $this->config = $config;
            $this->schedule_id = $schedule_id;
            $obj_config = new Config("d6");
            $is_graceperiod_active = $obj_config::getvar("IS_GRACEPERIOD");
            $grace_period_config = $obj_config::get_grace_period_config();

            if ($is_graceperiod_active == 1 && $grace_period_config['RESERVED']['is_grace_period_active'] == 'YES') {
                $this->get_process_data();
            }

        }
    }

    public function get_process_data(){

        $config_data = json_decode($this->config,true);
        // debug($config_data);
        $where = '';
        if(key_exists('afterHrs',$config_data)){
            $hrs = $config_data['afterHrs'];
            $where = ' AND TIMESTAMPDIFF(HOUR, st.reserved_on, (NOW() - INTERVAL 60 MINUTE)) >= '.$hrs;
        }elseif(key_exists('afterMin',$config_data)){
            $min = $config_data['afterMin'];
            $where = ' AND TIMESTAMPDIFF(MINUTE, st.reserved_on, (NOW() - INTERVAL 60 MINUTE)) >= '.$min;
        }


        // watchdog('SCH_MGR',print_r($config_data,true));
        $sql = "SELECT
                    u.mail,
                    u.uid,
                    st.id as transaction_id,
                    st.customer_id exhib_nid,
                    st.reserved_on,
                    st.reserved_by,
                    st.reserved_grace_minutes,
                    cxrl.primary_user_ref_id,
                    TIMESTAMPDIFF(MINUTE, st.reserved_on, (NOW() - INTERVAL 60 MINUTE)) AS MinutesElapsed,
                    TIMESTAMPDIFF(HOUR, st.reserved_on, (NOW() - INTERVAL 60 MINUTE)) AS HoursElapsed
                FROM cnx_stand_transaction st
                INNER JOIN cnx_exhibitor cxex ON cxex.external_ref_id = st.customer_id
                INNER JOIN users u ON cxex.user_ref_id = u.uid AND u.status = 1
                LEFT JOIN cnx_reminder_logs cxrl ON cxrl.primary_user_ref_id = cxex.user_ref_id  AND cxrl.schedule_id = ".$this->schedule_id." AND cxrl.transaction_id = st.id
                WHERE st.status IN ('RESERVED','CONTRACT_SUBMITTED')
                AND st.deleted = 0
                AND cxrl.primary_user_ref_id IS NULL
                ".$where."
                ORDER BY st.id DESC;";

        // echo $sql;
        // watchdog('SCH_MGR',$sql);

        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        // debug($result,1);
        // echo '----------';
        if($result){
            foreach($result as $res){
                $this->obj_users[] = array('primary_email' => $res['mail'],
                                    'exhibitor_name' => $res['reserved_by'],
                                    'deadline_date' => date('d-M-Y H:i:s', strtotime(' + '.$res['reserved_grace_minutes'].' min ', strtotime($res['reserved_on']))),
                                    'transaction_id' => $res['transaction_id'],
                                    'exhibitor_urn' => $res['exhib_nid'],
                                    'exhibitor_uid' => $res['uid']);
            }
        }
    }
}