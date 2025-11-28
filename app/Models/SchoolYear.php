<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the enrollments for this school year.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the current school year.
     */
    public static function current()
    {
        return static::where('is_current', true)->first();
    }

    /**
     * Scope for active school years.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
