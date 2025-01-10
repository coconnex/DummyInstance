<?php
namespace Coconnex\API\IFPSS\StandTransaction\Models\ResponseModels;

Class StandTransactionResponseModel{
    public $stand_transaction_id;
    public $customer_id;
    public $stand_ref_id;
    public $external_ref_id;
    public $product_key;
    public $product_name;
    public $description;
    public $additional_info;
    public $quantity;
    public $rate;
    public $total;
    public $pricing_data;
    public $status;
    public $previous_status;
    public $notes;
    public $reserved_grace_minutes;
    public $signing_grace_minutes;
    public $reserved_on;
    public $reserved_by;
    public $contract_submitted_on;
    public $contract_submitted_by;
    public $cancelled_on;
    public $cancelled_by;
    public $contract_cancellation_requested_on;
    public $contract_cancellation_requested_by;
    public $transaction_actions;
    public $reason;
}