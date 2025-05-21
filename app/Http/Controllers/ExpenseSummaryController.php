<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ExpenseService;
use App\Models\User;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
