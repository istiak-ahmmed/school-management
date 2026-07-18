<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function employee()
    {
        if ($this->employee_type === 'teacher') {
            return $this->belongsTo(Teacher::class, 'employee_id');
        }
        return $this->belongsTo(Staff::class, 'employee_id');
    }
}
