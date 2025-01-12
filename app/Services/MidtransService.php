<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans Configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap Payment URL
     * 
     * @param array $transactionDetails
     * @return string
     */
    public function createSnapUrl(array $transactionDetails)
    {
        return Snap::createTransaction($transactionDetails)->redirect_url;
    }

    /**
     * Retrieve Transaction Status
     * 
     * @param string $transactionId
     * @return mixed
     */
    public function getTransactionStatus(string $transactionId)
    {
        return \Midtrans\Transaction::status($transactionId);
    }
}
