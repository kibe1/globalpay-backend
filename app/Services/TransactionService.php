<?php

namespace App\Services;

use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;

class TransactionService
{
      /**
     * Create a new transaction with the provided data.
     *
     * @param array $data
     * @return Transaction|null
     * @throws Exception
     */

    public function createTransaction(array $data): ?Transaction
    {
        try {

            $transactionDetails = [
                'payment_mode' => $data['payment_mode'],
                'customer_payment_mode' => $data['customer_payment_mode'],
                'amount' => $data['amount'],
                'time_payment_made' => $data['time_payment_made'],
                'time_payment_processed' => $data['time_payment_processed'],
            ];

            $transaction = Transaction::create($transactionDetails);

            return $transaction;

        } catch (\Exception $e) {
            Log::error('Failed to create transaction', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

    }

    /**
     * Get a transaction by its transaction code.
     *
     * @param string $transactionCode
     * @return Transaction|null
     * @throws Exception
     */
    public function getTransactionByCode(string $transactionCode): ?Transaction
    {
        try {
            // Retrieve the transaction from the database using the transaction code
            return Transaction::where('transaction_code', $transactionCode)->first();
        } catch (\Exception $e) {
            throw new Exception('Failed to fetch transaction: ' . $e->getMessage());
        }
    }

}
