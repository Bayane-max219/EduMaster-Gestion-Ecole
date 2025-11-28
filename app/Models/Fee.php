<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'type',
        'frequency',
        'class_level',
        'is_mandatory',
        'due_date',
        'school_year_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'due_date' => 'date',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
