<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerQuota extends Model
{
    use HasFactory;

    protected $fillable = ['supervisor_hunting_period_id', 'lecturer_id', 'total_quota', 'remaining_quota'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function supervisorHuntingPeriod()
    {
        return $this->belongsTo(SupervisorHuntingPeriod::class, 'supervisor_hunting_period_id');
    }
}
