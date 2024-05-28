<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'string|nullable',
            'start' => 'date|nullable',
            'end' => 'date|nullable',
            'per_page' => 'integer|min:0|nullable',
            'order_by' => 'string|nullable',
            'ordering' => Rule::in(['asc', 'desc']),
        ]);

        $transactions = Transaction::query();
        if ($validated['search'] ?? null) {
            $transactions = $transactions->where('category', 'LIKE', "%{$validated['search']}%")
                ->orWhere('description', 'LIKE', "%{$validated['search']}%");
        }
        if ($validated['start'] ?? null) {
            $transactions = $transactions->where('date', '>=', $validated['start']);
        }
        if ($validated['end'] ?? null) {
            $transactions = $transactions->where('date', '<=', $validated['end']);
        }
        $order_by = $validated['order_by'] ?? 'date';
        $ordering = $validated['ordering'] ?? 'desc';
        $transactions = $transactions
            ->orderBy($order_by, $ordering)
            ->paginate($validated['per_page'] ?? 10);
        return TransactionResource::collection($transactions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $transaction = Transaction::create($request->validated());
        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response(null, 204);
    }
}
