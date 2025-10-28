<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Return all transactions (with categories) ordered by date.
     */
    public function index(): JsonResponse
    {
        $transactions = Transaction::with('category')
            ->orderBy('date', 'asc')
            ->get();

        // Add running total for "Итого"
        $runningTotal = 0;
        $transactions->transform(function ($t) use (&$runningTotal) {
            $runningTotal += $t->type === 'income' ? $t->amount : -$t->amount;
            $t->total = $runningTotal;
            return $t;
        });

        return response()->json($transactions);
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => ['nullable', 'date'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::create($validator->validated());

        // Load category relation so frontend can see it immediately
        $transaction->load('category');

        return response()->json($transaction, 201);
    }

    /**
     * Update an existing transaction.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $transaction = Transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => ['nullable', 'date'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update($validator->validated());
        $transaction->load('category');

        return response()->json($transaction);
    }

    /**
     * Delete a transaction.
     */
    public function destroy($id): JsonResponse
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
