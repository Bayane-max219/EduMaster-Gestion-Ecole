<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_number',
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'qualification',
        'specialization',
        'hire_date',
        'salary',
        'status',
        'photo',
        'emergency_contact',
        'emergency_phone',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subjects taught by this teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects');
    }

    /**
     * Get the classes assigned to this teacher.
     */
    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'teacher_classes');
    }

    /**
     * Get the teacher's timetable entries.
     */
    public function timetableEntries()
    {
        return $this->hasMany(TimetableEntry::class);
    }

    /**
     * Get the grades given by this teacher.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the attendances recorded by this teacher.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get age attribute.
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Scope for active teachers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for teachers teaching a specific subject.
     */
    public function scopeTeachingSubject($query, $subjectId)
    {
        return $query->whereHas('subjects', function ($q) use ($subjectId) {
            $q->where('subjects.id', $subjectId);
        });
    }
}
