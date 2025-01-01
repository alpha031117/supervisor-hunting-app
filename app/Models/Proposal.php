<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = ['lecturer_id', 'proposal_title', 'proposal_description', 'status'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}

