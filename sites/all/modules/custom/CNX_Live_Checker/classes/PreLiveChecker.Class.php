<?php

use Coconnex\Utils\Config\Config;

require_once(dirname(dirname(dirname(__FILE__))) . "/Classes/Coconnex/Utils/Config/Config.Class.php");

class PreLiveChecker
{

    public static function eventInfo()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "Event Name", "col2" => $obj_config::getvar("site_name"));
        $returnval[] = array("col1" => "Email sender name", "col2" => $obj_config::getvar("site_mail"));
        return $returnval;
    }
    public static function orgInfo()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "Organising Company Name", "col2" => $obj_config::getvar("ORGANISER_NAME"));
        return $returnval;
    }
    public static function sysConfig()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "Landing page url", "col2" => $obj_config::getvar("CUSTOM_LANDING_PAGE"));
        $returnval[] = array("col1" => "Waiting List feature is active", "col2" => ($obj_config::getvar("IS_WAITINGLIST") == 1) ? "Yes" : "No");
        $returnval[] = array("col1" => "Grace Period feature is active", "col2" => ($obj_config::getvar("IS_GRACEPERIOD") == 1) ? "Yes" : "No");
        $returnval[] = array("col1" => "Flag to allow repeat user registration", "col2" => $obj_config::getvar("email_mode"));
        $returnval[] = array("col1" => "Maximum words count", "col2" => $obj_config::getvar("MAX_WORD_LENGTH_FOR_REASON"));
        return $returnval;
    }
    public static function recaptchaFeat()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "Site key", "col2" => $obj_config::getvar("reCAPTCHA_site_key"));
        $returnval[] = array("col1" => "Secret key", "col2" => $obj_config::getvar("reCAPTCHA_secret_key"));
        return $returnval;
    }
    public static function standProp()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "Display stand dimensions", "col2" => ($obj_config::getvar("DISPLAY_STAND_DIMS") == 1) ? "Yes" : "No");
        $returnval[] = array("col1" => "Display stand area", "col2" => ($obj_config::getvar("DISPLAY_STAND_AREA") == 1) ? "Yes" : "No");
        $returnval[] = array("col1" => "Display stand name", "col2" => ($obj_config::getvar("DISPLAY_STAND_NAME") == 1) ? "Yes" : "No");
        return $returnval;
    }
    public static function standColor()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "Searched stands colour", "col2" => $obj_config::getvar("STANDS_SEARCHED_COLOUR"), "col2_attr" => 'background-color:' . $obj_config::getvar("STANDS_SEARCHED_COLOUR"));
        $returnval[] = array("col1" => "Added stands colour", "col2" => $obj_config::getvar("STANDS_IN_CART_COLOUR"), "col2_attr" => 'background-color:' . $obj_config::getvar("STANDS_IN_CART_COLOUR"));
        $returnval[] = array("col1" => "Selected stands colour", "col2" => $obj_config::getvar("STANDS_SELECTED_COLOUR"), "col2_attr" => 'background-color:' . $obj_config::getvar("STANDS_SELECTED_COLOUR"));

        return $returnval;
    }
    public static function noUser()
    {
        $obj_config = new Config("d6");
        $returnval = array();

        $sql = "SELECT COUNT(uid) AS col1 FROM users WHERE NAME != 'organiser' AND uid > 1";
        $rs = $obj_config::getResultset($sql);

        while ($row = $obj_config::getRow($rs)) {
            $returnval[] = array('col1' => (int) $row->col1);
        }

        return $returnval;
    }
    public static function mailSetting()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $returnval[] = array("col1" => "SMTP module on or off", "col2" => ($obj_config::getvar("smtp_on") == 1) ? "On" : "Off");
        $returnval[] = array("col1" => "SMTP server", "col2" => $obj_config::getvar("smtp_host"));
        $returnval[] = array("col1" => "SMTP backup server", "col2" => $obj_config::getvar("smtp_hostbackup"));
        $returnval[] = array("col1" => "SMTP port", "col2" => $obj_config::getvar("smtp_port"));
        $returnval[] = array("col1" => "Use encrypted protocol", "col2" => $obj_config::getvar("smtp_protocol"));
        $returnval[] = array("col1" => "Username", "col2" => $obj_config::getvar("smtp_username"));
        $returnval[] = array("col1" => "Password", "col2" => $obj_config::getvar("smtp_password"));
        $returnval[] = array("col1" => "E-mail from address", "col2" => $obj_config::getvar("smtp_from"));
        $returnval[] = array("col1" => "E-mail from name", "col2" => $obj_config::getvar("smtp_fromname"));
        return $returnval;
    }
    public static function withoutExhiref()
    {
        $obj_config = new Config("d6");
        $returnval = array();

        $sql = "SELECT u.name as uname, ex.company_name cname
        FROM users u
        LEFT JOIN cnx_exhibitor ex ON ex.user_ref_id = u.uid
        WHERE u.uid > 3 AND ex.company_name IS NULL";
        $rs = $obj_config::getResultset($sql);

        while ($row = $obj_config::getRow($rs)) {
            $arr = array();
            $arr['col1'] = $row->uname;
            $arr['col2'] = $row->cname;
            $returnval[] = $arr;
        }
        return $returnval;
    }
    public static function standLocked()
    {
        $obj_config = new Config("d6");
        $returnval = array();

        $sql = "SELECT COUNT(stk.id) col1
        FROM cnx_stands_taken stk
        LEFT JOIN cnx_stand_transaction tran ON tran.stand_ref_id = stk.stand_ref_id 
        WHERE tran.status <> 'CANCELLED' AND tran.id IS NULL;";
        $rs = $obj_config::getResultset($sql);

        $row = $obj_config::getRow($rs);
        $count = (int) $row->col1;

        if ($count === 0) {
            return [];
        }
        $returnval[] = array('col1' => $count);

        return $returnval;
    }
    public static function standBookedmorethanone()
    {
        $obj_config = new Config("d6");

        $returnval = array();
        $sql = "SELECT COUNT(tran.stand_ref_id) counts, ex.company_name cname
        FROM cnx_stand_transaction tran
        INNER JOIN cnx_exhibitor ex ON ex.external_ref_id = tran.customer_id 
        WHERE STATUS <> 'CANCELLED' 
        GROUP BY ex.id 
        HAVING counts > 1;";

        $rs = $obj_config::getResultset($sql);

        while ($row = $obj_config::getRow($rs)) {
            $arr = array();
            $arr['col1'] = $row->counts;
            $arr['col2'] = $row->cname;
            $returnval[] = $arr;
        }
        return $returnval;
    }
}
