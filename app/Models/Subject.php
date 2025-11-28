<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'coefficient',
        'color',
        'is_active',
    ];

    protected $casts = [
        'coefficient' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects');
    }
}
