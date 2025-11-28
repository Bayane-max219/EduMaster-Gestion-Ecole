<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClassRoomController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassRoom::orderBy('level')->orderBy('name')->get();

        $classesData = $classes->map(function ($c) {
            // Professeur principal (si la table existe)
            $principal = null;
            if (Schema::hasTable('teacher_classes') && Schema::hasTable('teachers')) {
                try {
                    $query = DB::table('teacher_classes')
                        ->join('teachers', 'teacher_classes.teacher_id', '=', 'teachers.id')
                        ->where('teacher_classes.class_id', $c->id);

                    if (Schema::hasColumn('teacher_classes', 'is_class_teacher')) {
                        $query->where('teacher_classes.is_class_teacher', true);
                    }

                    $principal = $query->value(DB::raw("CONCAT(teachers.first_name,' ',teachers.last_name)"));
                } catch (\Throwable $e) {
                    $principal = null;
                }
            }

            // Nombre d'élèves
            $studentCount = 0;

            // 1) Si la table d'inscriptions existe, on l'utilise en priorité
            if (Schema::hasTable('enrollments')) {
                try {
                    $studentCount = DB::table('enrollments')
                        ->where('class_id', $c->id)
                        ->count();
                } catch (\Throwable $e) {
                    $studentCount = 0;
                }
            }

            // 2) Si aucun élève trouvé via enrollments, on se rabat sur les élèves
            //    en utilisant le champ texte students.classe (niveau) et, si dispo,
            //    le nom complet de la classe.
            if ($studentCount === 0 && Schema::hasTable('students')) {
                try {
                    $studentCount = DB::table('students')
                        ->whereNotNull('classe')
                        ->where(function ($q) use ($c) {
                            $q->where('classe', $c->name);

                            if (Schema::hasColumn('class_rooms', 'level')) {
                                $q->orWhere('classe', $c->level);
                            }
                        })
                        ->count();
                } catch (\Throwable $e) {
                    $studentCount = 0;
                }
            }

            // Année scolaire
            $yearName = null;
            if (Schema::hasTable('school_years')) {
                $yearName = DB::table('school_years')->where('id', $c->school_year_id)->value('name');
            }

            return [
                'model' => $c,
                'principal' => $principal,
                'student_count' => $studentCount,
                'school_year' => $yearName,
            ];
        });

        return view('classes.index', ['classesData' => $classesData]);
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();
        return view('classes.create', compact('schoolYears'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'section' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:0',
            'room_number' => 'nullable|string|max:50',
            'school_year_id' => 'required|integer|exists:school_years,id',
            'status' => 'required|in:active,inactive',
        ]);

        ClassRoom::create($data);

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès.');
    }

    public function show(ClassRoom $class)
    {
        // Professeur principal (optionnel)
        $principal = null;
        if (Schema::hasTable('teacher_classes') && Schema::hasTable('teachers')) {
            try {
                $query = DB::table('teacher_classes')
                    ->join('teachers', 'teacher_classes.teacher_id', '=', 'teachers.id')
                    ->where('teacher_classes.class_id', $class->id);
                if (Schema::hasColumn('teacher_classes', 'is_class_teacher')) {
                    $query->where('teacher_classes.is_class_teacher', true);
                }
                $principal = $query->value(DB::raw("CONCAT(teachers.first_name,' ',teachers.last_name)"));
            } catch (\Throwable $e) {
                $principal = null;
            }
        }

        // Compteur élèves (optionnel)
        $studentCount = 0;

        // 1) Inscriptions formelles si la table existe
        if (Schema::hasTable('enrollments')) {
            try {
                $studentCount = DB::table('enrollments')
                    ->where('class_id', $class->id)
                    ->count();
            } catch (\Throwable $e) {
                $studentCount = 0;
            }
        }

        // 2) Fallback sur les élèves avec une classe texte
        if ($studentCount === 0 && Schema::hasTable('students')) {
            try {
                $studentCount = DB::table('students')
                    ->whereNotNull('classe')
                    ->where(function ($q) use ($class) {
                        $q->where('classe', $class->name);

                        if (Schema::hasColumn('class_rooms', 'level')) {
                            $q->orWhere('classe', $class->level);
                        }
                    })
                    ->count();
            } catch (\Throwable $e) {
                $studentCount = 0;
            }
        }

        // Année scolaire
        $yearName = null;
        if (Schema::hasTable('school_years')) {
            $yearName = DB::table('school_years')->where('id', $class->school_year_id)->value('name');
        }

        return view('classes.show', [
            'classRoom' => $class,
            'principal' => $principal,
            'studentCount' => $studentCount,
            'schoolYearName' => $yearName,
        ]);
    }

    public function edit(ClassRoom $class)
    {
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();
        return view('classes.edit', ['classRoom' => $class, 'schoolYears' => $schoolYears]);
    }

    public function update(Request $request, ClassRoom $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'section' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:0',
            'room_number' => 'nullable|string|max:50',
            'school_year_id' => 'required|integer|exists:school_years,id',
            'status' => 'required|in:active,inactive',
        ]);

        $class->update($data);

        return redirect()->route('classes.index')->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(ClassRoom $class)
    {
        $name = $class->name;
        $class->delete();
        return redirect()->route('classes.index')->with('success', "Classe {$name} supprimée avec succès.");
    }
}
