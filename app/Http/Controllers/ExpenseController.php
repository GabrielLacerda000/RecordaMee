<?php

namespace App\Http\Controllers;

use ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $expenseService;
    public function __construct(ExpenseService $expenseService) {
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
        try {
            $expense = $this->expenseService->createExpense($request);
            return response()->json([
                'status' => 'success',
                'message' => 'Despesa criada com sucesso',
                'data' => $expense
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao criar despesa',
                'data' => null,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $expense = $this->expenseService->showExpense($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Despesa recuperada com sucesso',
                'data' => $expense
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Despesa não encontrada',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar despesa',
                'data' => null,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $expense = $this->expenseService->updateExpense($id, $request);
            return response()->json([
                'status' => 'success',
                'message' => 'Despesa atualizada com sucesso',
                'data' => $expense
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Despesa não encontrada',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar despesa',
                'data' => null,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->expenseService->deleteExpense($id);
            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Despesa removida com sucesso',
                    'data' => null
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não foi possível remover a despesa',
                    'data' => null
                ], 400);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Despesa não encontrada',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao remover despesa',
                'data' => null,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
