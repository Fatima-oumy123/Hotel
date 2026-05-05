<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id','action','model_type','model_id',
        'old_values','new_values','ip_address','user_agent',
    ];
    protected $casts = ['old_values' => 'array', 'new_values' => 'array'];

    public function user() { return $this->belongsTo(User::class); }

    public function getActionIconAttribute(): string
    {
        if (str_contains($this->action, 'login'))  return '🔐';
        if (str_contains($this->action, 'logout')) return '🚪';
        if (str_contains($this->action, 'POST'))   return '➕';
        if (str_contains($this->action, 'PUT') || str_contains($this->action, 'PATCH')) return '✏️';
        if (str_contains($this->action, 'DELETE')) return '🗑️';
        return '📋';
    }
}
