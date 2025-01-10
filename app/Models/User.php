<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'program_id',
        'first_login',
        'role',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function researchGroup()
    {
        return $this->belongsTo(ResearchGroup::class);
    }

    public function lecturerQuotas()
    {
        return $this->hasMany(LecturerQuota::class, 'lecturer_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function isLecturer($user)
    {
        return $user->role === 'lecturer';
    }

    public function timetable()
    {

        return $this->hasOne(Timetable::class, 'lecturer_id');
    }
}
