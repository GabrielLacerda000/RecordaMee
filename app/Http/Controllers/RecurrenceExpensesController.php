<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ExpenseService;

class RecurrenceExpensesController extends Controller
{

    protected $expenseService;

    public function __construct(ExpenseService $expenseService) {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        $recurrencyExpenses = $this->expenseService->getNextRecurrencyExpenses();

        if(!$recurrencyExpenses) {
            return response()->json([
                'status' => 'error',
                'message' => 'Despesas recorrentes não encontradas',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Despesas recorrentes recuperadas com sucesso',
            'data' => $recurrencyExpenses
        ], 200);
    }
}