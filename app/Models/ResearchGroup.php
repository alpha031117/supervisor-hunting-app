<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchGroup extends Model
{
    use HasFactory;

    protected $fillable = ['group_name', 'group_description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

