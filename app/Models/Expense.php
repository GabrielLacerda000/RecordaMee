<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'name',
        'due_date',
        'status_id',
        'recurrence_id',
        'category_id',
        'amount',
        'payment_date',
    ];
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
}
