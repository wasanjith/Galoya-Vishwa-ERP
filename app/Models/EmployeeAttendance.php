<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'status',
        'check_in_at',
        'check_out_at',
        'worked_hours',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'worked_hours' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Employee, EmployeeAttendance>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function statusEnum(): ?AttendanceStatus
    {
        return AttendanceStatus::tryFrom((string) $this->status);
    }

    public function salaryUnits(): float
    {
        return $this->statusEnum()?->multiplier() ?? 0;
    }

    public function dailyPayout(): float
    {
        $employee = $this->employee;

        if ($employee === null) {
            return 0;
        }

        return round($this->salaryUnits() * $employee->dailyRate(), 2);
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->statusEnum()?->label());
    }
}


