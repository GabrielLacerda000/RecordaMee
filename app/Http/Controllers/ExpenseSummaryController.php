<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ExpenseService;

class ExpenseSummaryController extends Controller
{
    protected $expenseService;
    public function __construct(ExpenseService $expenseService) {
        $this->expenseService = $expenseService;
    }

    public function index() {
        $summary = $this->expenseService->getExpensesSummary();

        return response()->json([
           'status' =>'success',
           'message' => 'Resumo de despesas recuperado com sucesso',
            'data' => $summary
        ], 200);
    }
}
