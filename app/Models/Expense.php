<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'due_date',
        'status_id',
        'recurrence_id',
        'category_id',
        'amount',
        'payment_date',
        'user_id',
        'isPaid',
        'parent_expense_id'
    ];
    protected $hidden = ['status_id', 'category_id', 'recurrence_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function recurrence()
    {
        return $this->belongsTo(Recurrence::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(Expense::class, 'parent_expense_id');
    }
       
    public function children()
    {
        return $this->hasMany(Expense::class, 'parent_expense_id');
    }
}
