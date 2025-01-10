<?
namespace Coconnex\API\Reminders\Managers;

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/DBFactory/Db.Class.php");

use Coconnex\DBFactory\Db;

class FormReminderManager extends Db{
    public $config;
    // public $primary_email;
    // public $exhibitor_name;
    // public $form_name;
    // public $deadline_date;
    // public $exhibitor_urn;
    // public $exhibitor_uid;
    public $obj_users = array();


    public function __construct($config)
    {
        if($config != ""){
            $this->config = $config;
            $this->get_process_data();
        }
    }

    public function get_process_data(){
        $config_data = json_decode($this->config);
        // debug($config_data);
        // watchdog('SCH_MGR',print_r($config_data,true));
        $sql = "SELECT
                    u.uid,
                    n.nid,
                    u.name as user_name,
                    u.mail as mail,
                    cte.field_ex_company_value company_name,
                    CONCAT(cte.field_ex_public_contactfirstname_value,' ',cte.field_ex_public_contactsurname_value) AS contact_name,
                    emf.title AS form_name,
                    emfexp.form_expiry AS deadline_date,
                    emd.form_id,
                    emd.status,
                    st.field_stand_type_description_value,
                    fna.form_id,
                    fna.uid na_uid
                FROM em_forms emf
                INNER JOIN cnx_form_expiry emfexp ON emfexp.form_id = emf.id
                    AND emfexp.user_type = 1
                INNER JOIN cnx_form_access cfa ON cfa.form_id=emf.id
                INNER JOIN cnx_form_access cfa2 ON cfa.form_id = cfa2.form_id
                LEFT JOIN node n ON n.type = 'exhibitor'
                LEFT JOIN users u ON u.uid = n.uid
                LEFT JOIN users_roles ur ON u.uid = ur.uid
                LEFT JOIN role r ON ur.rid = r.rid
                LEFT JOIN content_type_exhibitor cte ON cte.nid = n.nid
                LEFT JOIN contracts con ON con.exhib_id=u.uid
                    AND con.cancellation_flag = 0
                    AND con.isactive = 1
                    AND con.isContracted = 1
                LEFT JOIN stand_exhibitor stex ON con.contract_id = stex.contract_id
                    AND stex.is_cancelled = 0
                LEFT JOIN content_type_standtypes st ON st.nid = stex.stand_option_id
                    AND st.field_stand_type_is_active_value <> 0
                LEFT JOIN em_submitted_data emd ON emd.id  = (SELECT MAX(id) FROM em_submitted_data WHERE form_id = " .$config_data->form_id. "
                    AND cancelled IS NULL AND exhib_id = n.nid)
                LEFT JOIN cnx_eform_notapplicable fna ON fna.form_id = emf.id AND fna.uid = u.uid
                WHERE emf.id = ".$config_data->form_id."
                    AND r.name = 'exhibitor'
                    AND u.uid IS NOT NULL
                    AND ((emd.form_id IS NULL OR emd.status = 0) AND fna.uid IS NULL)
                    AND (cfa2.check_type = 'SPACE'
                    AND (cfa2.check_value = IF(st.field_stand_type_description_value = 'Shell Stand', 'SHELL','BARE') OR cfa2.check_value IS NULL))
                GROUP BY u.uid;";

        // echo $sql;
        // watchdog('SCH_MGR',$sql);

        $db = new Db();
        $result = $db->RetrieveRecord($sql);
        // debug($result);
        // echo '----------';
        if($result){
            foreach($result as $res){
                $this->obj_users[] = array('primary_email' => $res['mail'],
                                    'exhibitor_name' => $res['contact_name'],
                                    'form_name' => $res['form_name'],
                                    'deadline_date' => $res['deadline_date'],
                                    'exhibitor_urn' => $res['nid'],
                                    'exhibitor_uid' => $res['uid']);
            }
        }
    }
}