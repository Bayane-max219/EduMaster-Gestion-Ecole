<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'class_rooms';

    protected $fillable = [
        'name',
        'level',
        'section',
        'capacity',
        'room_number',
        'school_year_id',
        'status'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Récupérer toutes les classes uniques
     */
    public static function getAllClassNames()
    {
        return self::distinct()->pluck('name')->sort();
    }

    /**
     * Récupérer les classes par niveau
     */
    public static function getClassesByLevel($level)
    {
        return self::where('level', $level)->pluck('name');
    }

    /**
     * Vérifier si une classe existe
     */
    public static function classExists($className)
    {
        return self::where('name', $className)->exists();
    }

    /**
     * Année scolaire associée à la classe.
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
