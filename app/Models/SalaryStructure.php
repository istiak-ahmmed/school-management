<?php

namespace App\Models;

use App\Enums\EmployeeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_type',
        'employee_id',
        'basic_salary',
        'house_rent',
        'medical_allowance',
        'transport_allowance',
        'other_allowance',
        'deduction_provident',
        'deduction_tax',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to'   => 'date',
        'employee_type'  => EmployeeType::class,
    ];

    /**
     * Gross salary = basic + all allowances.
     */
    public function getGrossSalaryAttribute(): float
    {
        return $this->basic_salary
            + $this->house_rent
            + $this->medical_allowance
            + $this->transport_allowance
            + $this->other_allowance;
    }

    /**
     * Net salary = gross - all deductions.
     */
    public function getNetSalaryAttribute(): float
    {
        return $this->gross_salary
            - $this->deduction_provident
            - $this->deduction_tax;
    }
}
