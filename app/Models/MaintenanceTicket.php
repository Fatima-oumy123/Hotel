<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceTicket extends Model
{
    protected $fillable = [
        'room_id','reported_by','assigned_to','title','description',
        'priority','status','resolved_at','resolution_notes',
    ];
    protected $casts = ['resolved_at' => 'datetime'];

    public function room()     { return $this->belongsTo(Room::class); }
    public function reporter() { return $this->belongsTo(User::class, 'reported_by'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high'   => 'orange',
            'medium' => 'amber',
            'low'    => 'green',
            default  => 'gray',
        };
    }
}
