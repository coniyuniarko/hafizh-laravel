<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Http\Resources\BudgetResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'string|nullable',
            'per_page' => 'integer|min:0|nullable',
            'order_by' => 'string|nullable',
            'ordering' => Rule::in(['asc', 'desc']),
        ]);

        $budgets = Budget::query();
        if ($validated['search'] ?? null) {
            $budgets = $budgets->where('category', 'LIKE', "%{$validated['search']}%");
        }
        $order_by = $validated['order_by'] ?? 'date';
        $ordering = $validated['ordering'] ?? 'desc';
        $budgets = $budgets
            ->orderBy($order_by, $ordering)
            ->paginate($validated['per_page'] ?? 10);
        return BudgetResource::collection($budgets);
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
    public function store(StoreBudgetRequest $request)
    {
        $budget = Budget::create($request->validated());
        return new BudgetResource($budget);
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        return new BudgetResource($budget);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $budget->update($request->validated());
        return new BudgetResource($budget);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();
        return response(null, 204);
    }
}
