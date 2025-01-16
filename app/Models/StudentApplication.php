<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentApplication extends Model
{
    use HasFactory;


    protected $fillable = [
        'student_id',
        'lecturer_id',
        'lecturer_quota_id',
        'proposal_id',
        'proposal_title',
        'proposal_description',
        'student_title',
        'student_description',
        'remarks',
        'status',
        'decision_date',
    ];

    /**
     * Relationship: The application belongs to a student (user with a 'student' role).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relationship: The application belongs to a lecturer (user with a 'lecturer' role).
     */
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    /**
     * Relationship: The application references a specific lecturer quota.
     */
    public function lecturerQuota()
    {
        return $this->belongsTo(LecturerQuota::class, 'lecturer_quota_id');
    }

    /**
     * Relationship: The application may reference a specific proposal.
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
}
