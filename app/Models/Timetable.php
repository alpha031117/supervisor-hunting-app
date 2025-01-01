<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = ['lecturer_id', 'file_path'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}
