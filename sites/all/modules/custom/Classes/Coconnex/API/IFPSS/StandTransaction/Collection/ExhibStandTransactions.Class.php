<?php

namespace Coconnex\API\IFPSS\StandTransaction\Collection;

use Coconnex\API\IFPSS\StandTransaction\Managers\StandTransactionManager;
use Coconnex\DBFactory\Db;

class ExhibStandTransactions
{
    public $uid;
    public $customer_id;
    public $status;
    public $roles;
    public $exhib_stand_transactions = array();
    public $counts_by_status = array();


    public function __construct($user_id, $roles, $customer_id = 0, $status = 'ALL')
    {
        $this->uid = $user_id;
        if (is_array($roles)) $this->roles = $roles;
        $this->customer_id = $customer_id;
        $this->status = (is_array($status)) ? "'".implode("','",$status)."'" : strtoupper(trim($status));
        $this->set_counts_by_status();
        if ($customer_id != "") $this->get_exhib_stand_transactions();
        if ($customer_id == 0) $this->get_all_stand_transactions();
    }

    protected function get_exhib_stand_transactions()
    {
        if ($this->status == 'ALL') {
            $by_status = "";
        } else {
            $by_status = ($this->status == 'BOOKED') ? "AND (STATUS = '$this->status' OR STATUS = 'CONTRACT_SUBMITTED' OR STATUS = 'CONTRACT_ACCEPTED' OR STATUS = 'CONTRACT_COMPLETED' OR STATUS = 'CONTRACT_CANCELLATION_REQUESTED' OR STATUS = 'CONTRACT_CANCELLATION_APPROVED')" : "AND STATUS = '$this->status'";
        }

        $sql = "SELECT id
                FROM cnx_stand_transaction
                WHERE customer_id = '$this->customer_id'
                AND deleted = 0 " . $by_status ." ORDER BY id DESC";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $stand_transaction_id = $res['id'];
                $stand_transaction_manager = new StandTransactionManager($this->uid, $this->roles, $stand_transaction_id);
                $stand_transaction = $stand_transaction_manager->get_stand_transaction();
                // debug($stand_transaction);
                $this->exhib_stand_transactions[] = $stand_transaction;
            }
        }
    }

    protected function get_all_stand_transactions()
    {
        $sql = "SELECT id
                FROM cnx_stand_transaction
                WHERE STATUS IN (".$this->status.")
                AND deleted = 0 ORDER BY id DESC";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $stand_transaction_id = $res['id'];
                $stand_transaction_manager = new StandTransactionManager($this->uid, $this->roles, $stand_transaction_id);
                $stand_transaction = $stand_transaction_manager->get_stand_transaction();
                // debug($stand_transaction);
                $this->exhib_stand_transactions[] = $stand_transaction;
            }
        }
    }

    protected function set_counts_by_status()
    {
        $where = "";
        if($this->customer_id > 0){
            $where = " AND customer_id = ".$this->customer_id;
        }

        $sql = "SELECT
                (CASE
                    WHEN STATUS = 'CONTRACT_SUBMITTED' THEN 'BOOKED'
                    WHEN STATUS = 'CONTRACT_ACCEPTED' THEN 'BOOKED'
                    WHEN STATUS = 'CONTRACT_COMPLETED' THEN 'BOOKED'
                    WHEN STATUS = 'CONTRACT_CANCELLATION_REQUESTED' THEN 'BOOKED'
                    WHEN STATUS = 'CONTRACT_CANCELLATION_APPROVED' THEN 'BOOKED'
                    ELSE STATUS
                END) AS 'transaction_status'
                ,COUNT(id) AS 'count'
                FROM cnx_stand_transaction
                WHERE deleted = 0 ".$where."
                GROUP BY transaction_status";

        $db = new Db();
        $result = $db->RetrieveRecord($sql);

        if ($result) {
            foreach ($result as $res) {
                $this->counts_by_status[$res['transaction_status']] = $res['count'];
            }
        }
    }


}
