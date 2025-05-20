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

    
}
