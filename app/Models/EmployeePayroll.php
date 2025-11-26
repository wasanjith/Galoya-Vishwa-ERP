<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Enums\PayrollPeriodType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class EmployeePayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_type',
        'period_start_date',
        'period_end_date',
        'notes',
    ];

    protected $casts = [
        'period_start_date' => 'date',
        'period_end_date' => 'date',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'half_days' => 'integer',
        'absent_days' => 'integer',
        'leave_days' => 'integer',
        'attendance_units' => 'decimal:2',
        'calculated_salary' => 'decimal:2',
        'basic_salary_snapshot' => 'decimal:2',
        'daily_rate_snapshot' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Employee, EmployeePayroll>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted(): void
    {
        static::saving(function (self $payroll): void {
            $payroll->normalizePeriodDates();
            $payroll->applyAttendanceSnapshot();
        });
    }

    public function applyAttendanceSnapshot(): void
    {
        if (! $this->employee_id || ! $this->period_start_date || ! $this->period_end_date) {
            return;
        }

        /** @var Employee|null $employee */
        $employee = $this->employee()->first();

        if (! $employee) {
            return;
        }

        $startDate = $this->period_start_date->toDateString();
        $endDate = $this->period_end_date->toDateString();

        $attendances = $employee->attendances()
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get();

        $counters = collect(AttendanceStatus::cases())
            ->mapWithKeys(fn (AttendanceStatus $status): array => [$status->value => 0])
            ->all();

        $units = 0;

        $attendances->each(function (EmployeeAttendance $attendance) use (&$counters, &$units): void {
            $status = $attendance->statusEnum();

            if (! $status) {
                return;
            }

            $counters[$status->value]++;
            $units += $status->multiplier();
        });

        $this->working_days = $attendances->count();
        $this->present_days = $counters[AttendanceStatus::Present->value] ?? 0;
        $this->half_days = $counters[AttendanceStatus::HalfDay->value] ?? 0;
        $this->absent_days = $counters[AttendanceStatus::Absent->value] ?? 0;
        $this->leave_days = $counters[AttendanceStatus::Leave->value] ?? 0;
        $this->attendance_units = round($units, 2);

        $dailyRate = $employee->dailyRate();

        $this->basic_salary_snapshot = $employee->basic_salary ?? 0;
        $this->daily_rate_snapshot = $dailyRate;
        $this->calculated_salary = round($units * $dailyRate, 2);
    }

    protected function periodTypeLabel(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->periodTypeEnum()?->label());
    }

    public function periodTypeEnum(): ?PayrollPeriodType
    {
        return PayrollPeriodType::tryFrom((string) $this->period_type);
    }

    protected function normalizePeriodDates(): void
    {
        $periodType = $this->periodTypeEnum();
        $start = $this->period_start_date ? Carbon::parse($this->period_start_date->toDateString()) : null;
        $end = $this->period_end_date ? Carbon::parse($this->period_end_date->toDateString()) : null;

        if ($periodType === PayrollPeriodType::Daily) {
            $start ??= $end;
            $end ??= $start;

            if ($start) {
                $normalized = $start->copy();
                $this->period_start_date = $normalized;
                $this->period_end_date = $normalized->copy();
            }

            return;
        }

        if ($periodType === PayrollPeriodType::Monthly) {
            if ($start) {
                $start = $start->copy()->startOfMonth();
            } elseif ($end) {
                $start = $end->copy()->startOfMonth();
            }

            if ($end) {
                $end = $end->copy()->endOfMonth();
            } elseif ($start) {
                $end = $start->copy()->endOfMonth();
            }

            $this->period_start_date = $start;
            $this->period_end_date = $end;

            return;
        }

        if ($start && $end) {
            if ($end->lessThan($start)) {
                [$start, $end] = [$end, $start];
            }
        } elseif ($start && ! $end) {
            $end = $start->copy();
        } elseif ($end && ! $start) {
            $start = $end->copy();
        }

        $this->period_start_date = $start;
        $this->period_end_date = $end;
    }
}


