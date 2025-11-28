<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'class_id',
        'exam_type',
        'exam_name',
        'score',
        'max_score',
        'exam_date',
        'semester',
        'school_year_id',
        'notes',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'exam_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function scopeBySchoolYear($query, $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->when($classId, fn($q) => $q->where('class_id', $classId));
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->when($subjectId, fn($q) => $q->where('subject_id', $subjectId));
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->when($semester, fn($q) => $q->where('semester', $semester));
    }

    public function getScorePercentageAttribute()
    {
        if ($this->max_score && $this->score !== null) {
            return round(($this->score / $this->max_score) * 100, 2);
        }
        return null;
    }
}
