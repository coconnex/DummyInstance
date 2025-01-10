<?php

namespace Coconnex\API\IFPSS\BookingHistory\Collection;

include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/DBFactory/Db.Class.php");

use Coconnex\API\IFPSS\BookingHistory\Managers\BookingHistoryManager;
use Coconnex\DBFactory\Db;

class exhBookingHistory extends Db
{

    protected $uid;
    protected $customer_id;
    protected $stand_transaction_ref;
    public $booking_history = array();
    protected $vars;
    public $data;

    public function __construct($uid, $customer_id = null)
    {
        $this->vars = array();
        $this->data = array();
        $this->uid = $uid;
        $this->customer_id = $customer_id;
    }

    protected function getSearchCondns($postdata)
    {

        $where = '';
        $data = [];
        if (isset($_REQUEST['txtDescriptionFilter']) && $_REQUEST['txtDescriptionFilter'] != '') {
            $searchExhib = $_REQUEST['txtDescriptionFilter'];
            $where .= " AND (cbh.description LIKE '%%" . $searchExhib . "%%')";
            $this->vars['selected_txtDescriptionFilter'] = $_REQUEST['txtDescriptionFilter'];
        }

        if (isset($_REQUEST['txtUserFilter']) && $_REQUEST['txtUserFilter'] != '') {
            $searchUser = $_REQUEST['txtUserFilter'];
            $where .= " AND (cbh.modified_by_name LIKE '%%" . $searchUser . "%%')";
            $this->vars['selected_txtUserFilter'] = $_REQUEST['txtUserFilter'];
        }

        if (isset($_REQUEST['typeFilter']) && $_REQUEST['typeFilter'] != '') {
            $where .= " AND cbh.action='" . $_REQUEST['typeFilter'] . "'";
            $this->vars['selected_typeFilter'] = $_REQUEST['typeFilter'];
        }

        if ((isset($_REQUEST['txtDateFilterFrom']) && $_REQUEST['txtDateFilterFrom'] != '')
            && (isset($_REQUEST['txtDateFilterTo']) && $_REQUEST['txtDateFilterTo'] != '')
        ) {
            $fromdate = date_create($_REQUEST['txtDateFilterFrom']);
            $fromDate = date_format($fromdate, "Y-m-d H:i:s");

            $todate = date_create($_REQUEST['txtDateFilterTo']);
            $todate = date_format($todate, "Y-m-d H:i:s");
            // $toDate = date('Y-m-d H:i:s',strtotime($todate . "+1 days"));

            $where .= " AND cbh.created_on BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
            $this->vars['selected_txtDateFilterFrom'] = $_REQUEST['txtDateFilterFrom'];
            $this->vars['selected_txtDateFilterTo'] = $_REQUEST['txtDateFilterTo'];
        }
        // echo $where;exit;

        $data['selecetedValue'] = $this->vars;

        if ($this->customer_id != '') {
            $where .= " AND cst.customer_id = '" . $this->customer_id . "'";
        }

        $data['where'] = $where;

        return $data;
    }
    public function get_list($postdata)
    {

        $year = date("Y");
        $data = $this->getSearchCondns($postdata);
        $where = $data['where'];

        $sql = "SELECT
                cbh.id
                ,cbh.action
                ,cbh.description
                ,DATE_FORMAT(cbh.created_on, '%d-%m-%Y %H:%i:%s') AS created_date
                ,DATE_FORMAT(cbh.modified_on, '%d-%m-%Y %H:%i:%s') AS modified_date
                ,u.name as 'user'
                ,cbh.created_by_name
                ,cbh.modified_by_name
                FROM cnx_booking_history cbh
                JOIN cnx_stand_transaction cst ON cbh.stand_transaction_ref = cst.id
                LEFT JOIN users u on u.uid = cbh.created_by
                WHERE 1=1 " . $where . " ORDER BY cbh.created_on DESC";

        //  echo $sql; die;

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($data['selecetedValue'] != '' && !empty($data['selecetedValue'])) {
            $result['selectedValue'] =  $data['selecetedValue'];
        }
        return $result;
    }
    function get_action_list()
    {

        $sql = "SHOW COLUMNS FROM cnx_booking_history WHERE Field = 'action'";

        // Execute the SQL query
        $result = $this->RetrieveRecord($sql);
        if ($result) {
            $row = $result[0];

            preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
            $enumValues = explode(",", $matches[1]);


            $enumValues = array_map(function ($value) {
                return trim($value, "'");
            }, $enumValues);


            return $enumValues;
        }
    }

    public function get_list_by_stand()
    {
        $sql = "SELECT id
        FROM cnx_booking_history
        WHERE stand_transaction_ref = " . $this->stand_transaction_ref . "";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $booking_history_item_id = $res['id'];
                $booking_history_manager = new BookingHistoryManager($this->uid, $booking_history_item_id);
                $booking_history_item = $booking_history_manager->get_bookinghistory();
                $this->booking_history[] = $booking_history_item;
            }
        }
    }
}
