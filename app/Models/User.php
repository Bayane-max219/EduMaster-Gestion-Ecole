<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'is_active',
        'last_login_at',
        'classe',
        'date_naissance',
        'parent_tuteur',
        'adresse',
        'genre',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'date_naissance' => 'date',
    ];

    /**
     * Get the student record associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the teacher record associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the parent record associated with the user.
     */
    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is director
     */
    public function isDirector()
    {
        return $this->hasRole('director');
    }

    /**
     * Check if user is secretary
     */
    public function isSecretary()
    {
        return $this->hasRole('secretary');
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher()
    {
        return $this->hasRole('teacher');
    }

    /**
     * Check if user is parent
     */
    public function isParent()
    {
        return $this->hasRole('parent');
    }

    /**
     * Check if user is student
     */
    public function isStudent()
    {
        return $this->hasRole('student');
    }
}
