<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class TransactionsController extends Controller
{

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'payment_mode' => 'required|string',
            'customer_payment_mode' => 'required|string',
            'amount' => 'required|string',
            'time_payment_made' => 'required|string',
            'time_payment_processed' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        //process data via the transaction service
        $transactiontData  = $request->only(['payment_mode', 'customer_payment_mode', 'amount', 'time_payment_made', 'time_payment_processed']);

        try {

            $transaction = $this->transactionService->createTransaction($transactiontData);

            //return the transaction with transaction details as json
            return response()->json([
                'status' => 'success',
                'data' => $transaction,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the transaction.',
                'errors' => [
                    'error' => $e->getMessage()
                ],
                'status_code' => 500
            ], 500);
        }

    }

    /**
 * Fetch transaction details by transaction code from query params.
 *
 * @param \Illuminate\Http\Request $request
 * @return JsonResponse
 */
public function getByCode(Request $request): JsonResponse
{
    try {
        // Retrieve the transaction code from the query parameters
        $transactionCode = $request->query('transaction_code');

        if (!$transactionCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction code is required',
                'status_code' => 400
            ], 400);
        }

        // Call the service method to fetch the transaction by code
        $transaction = $this->transactionService->getTransactionByCode($transactionCode);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found',
                'status_code' => 404
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'transaction' => $transaction,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching the transaction.',
            'errors' => [
                'error' => $e->getMessage()
            ],
            'status_code' => 500
        ], 500);
    }
}



}
