<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // Relations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function maintenanceTickets()
    {
        return $this->hasMany(MaintenanceTicket::class, 'reported_by');
    }

    public function assignedTickets()
    {
        return $this->hasMany(MaintenanceTicket::class, 'assigned_to');
    }

    public function restaurantSales()
    {
        return $this->hasMany(RestaurantSale::class, 'cashier_id');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getRoleNameAttribute(): string
    {
        return $this->getRoleNames()->first() ?? 'Utilisateur';
    }
}
