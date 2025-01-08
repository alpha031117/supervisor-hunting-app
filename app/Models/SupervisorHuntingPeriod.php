<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorHuntingPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'semester'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function lecturerQuotas()
    {
        return $this->hasMany(LecturerQuota::class);
    }
}
