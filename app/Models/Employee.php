<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name','last_name','email','phone','position','department',
        'salary','hire_date','end_date','status','id_number','contract_type',
    ];
    protected $casts = ['hire_date' => 'date', 'end_date' => 'date', 'salary' => 'decimal:2'];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active'   => 'badge-success',
            'on_leave' => 'badge-warning',
            'inactive' => 'badge-danger',
            default    => 'badge-secondary',
        };
    }

    public function getYearsOfServiceAttribute(): int
    {
        return $this->hire_date->diffInYears(now());
    }

    public function shifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function tasks()
    {
        return $this->hasMany(EmployeeTask::class);
    }
}
