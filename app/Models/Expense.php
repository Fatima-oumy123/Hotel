<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'title','description','amount','category',
        'supplier','expense_date','approved_by','receipt_path',
    ];
    protected $casts = ['expense_date' => 'date', 'amount' => 'decimal:2'];

    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' FCFA';
    }
}
