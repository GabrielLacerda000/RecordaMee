<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService as ServicesExpenseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $expenseService;
    public function __construct(ServicesExpenseService $expenseService) {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        $expenses = $this->expenseService->getExpenses();

        return response()->json([
            'status' => 'success',
            'message' => 'Despesas recuperadas com sucesso',
            'data' => $expenses
        ], 200);
    }

    public function store(Request $request)
    {
        $expense = $this->expenseService->createExpense($request);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Despesa criada com sucesso',
            'data' => $expense
        ], 201);
    }

    public function show($id)
    {
        $expense = $this->expenseService->showExpense($id);    

        if(!$expense) {
            return response()->json([
                'status' => 'error',
                'message' => 'Despesa não encontrada',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Despesa recuperada com sucesso',
            'data' => $expense
        ], 200);
    }

    public function update($id, Request $request)
    {
        $expense = $this->expenseService->updateExpense($id, $request);

        if(!$expense) {
            return response()->json([
               'status' => 'error',
               'message' => 'Despesa não encontrada',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Despesa atualizada com sucesso',
            'data' => $expense
        ], 200);
    }

    public function destroy($id)
    {
        $deleted = $this->expenseService->deleteExpense($id);

        if (!$deleted) {
            return response()->json([
              'status' => 'error',
              'message' => 'Despesa não encontrada',
                'data' => null
            ]);
        } 

        return response()->json([
            'status' => 'success',
            'message' => 'Despesa removida com sucesso',
            'data' => null
        ], 200);
    }
}