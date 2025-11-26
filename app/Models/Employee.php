<?php

namespace App\Models;

use App\Enums\EmployeeDepartment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'address',
        'id_card_number',
        'mobile_number',
        'department',
        'basic_salary',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
    ];

    /**
     * @return HasMany<EmployeeAttendance>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    /**
     * @return HasMany<EmployeePayroll>
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(EmployeePayroll::class);
    }

    public function dailyRate(): float
    {
        return round((float) ($this->basic_salary ?? 0), 2);
    }

    public function departmentLabel(): ?string
    {
        return EmployeeDepartment::tryFrom((string) $this->department)?->label();
    }

    protected function departmentEnum(): Attribute
    {
        return Attribute::get(fn (): ?EmployeeDepartment => EmployeeDepartment::tryFrom((string) $this->department));
    }
}


