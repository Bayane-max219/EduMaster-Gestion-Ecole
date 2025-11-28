<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'name',
        'email',
        'phone',
        'classe',
        'date_naissance',
        'parent_tuteur',
        'adresse',
        'genre',
        'is_active',
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'address',
        'emergency_contact',
        'emergency_phone',
        'photo',
        'enrollment_date',
        'status',
        'medical_info',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    /**
     * Get the user that owns the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student's enrollments.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the student's current enrollment.
     */
    public function currentEnrollment()
    {
        return $this->hasOne(Enrollment::class)
                    ->where('school_year_id', SchoolYear::current()->id ?? null)
                    ->where('status', 'active');
    }

    /**
     * Get the student's attendances.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the student's grades.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the student's payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the student's disciplinary records.
     */
    public function disciplinaryRecords()
    {
        return $this->hasMany(DisciplinaryRecord::class);
    }

    /**
     * Get the student's parents.
     */
    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'student_parent');
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
     * Scope for active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for students in a specific class.
     */
    public function scopeInClass($query, $classId)
    {
        return $query->whereHas('currentEnrollment', function ($q) use ($classId) {
            $q->where('class_id', $classId);
        });
    }
}
