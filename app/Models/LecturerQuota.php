<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerQuota extends Model
{
    use HasFactory;

    protected $fillable = ['semester', 'lecturer_id', 'total_quota', 'remaining_quota'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}
