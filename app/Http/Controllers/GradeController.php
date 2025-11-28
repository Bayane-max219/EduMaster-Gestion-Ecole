<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['class_id', 'subject_id', 'semester', 'student_id', 'teacher_id', 'school_year_id']);
        $currentYearId = $filters['school_year_id'] ?? (SchoolYear::current()->id ?? null);

        if (Schema::hasTable('grades')) {
            $query = Grade::with(['student', 'subject', 'teacher', 'classRoom', 'schoolYear']);

            if ($currentYearId) {
                $query->where('school_year_id', $currentYearId);
            }

            if (!empty($filters['class_id'])) {
                $query->where('class_id', $filters['class_id']);
            }
            if (!empty($filters['subject_id'])) {
                $query->where('subject_id', $filters['subject_id']);
            }
            if (!empty($filters['semester'])) {
                $query->where('semester', $filters['semester']);
            }
            if (!empty($filters['student_id'])) {
                $query->where('student_id', $filters['student_id']);
            }
            if (!empty($filters['teacher_id'])) {
                $query->where('teacher_id', $filters['teacher_id']);
            }

            $grades = $query->orderByDesc('exam_date')->paginate(15)->withQueryString();
        } else {
            $grades = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        // Dropdown data (tolerant to missing tables)
        $classes = Schema::hasTable('class_rooms') ? ClassRoom::orderBy('level')->orderBy('name')->get() : collect();
        $subjects = Schema::hasTable('subjects') ? Subject::where('is_active', true)->orderBy('name')->get() : collect();
        $teachers = Schema::hasTable('teachers') ? Teacher::orderBy('last_name')->orderBy('first_name')->get() : collect();
        $students = Schema::hasTable('students') ? Student::orderBy('last_name')->orderBy('first_name')->get() : collect();
        $schoolYears = Schema::hasTable('school_years') ? SchoolYear::orderByDesc('start_date')->get() : collect();

        return view('grades.index', compact(
            'grades', 'classes', 'subjects', 'teachers', 'students', 'schoolYears', 'filters', 'currentYearId'
        ));
    }

    public function create()
    {
        $currentYearId = SchoolYear::current()->id ?? null;
        $classes = ClassRoom::orderBy('level')->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->orderBy('first_name')->get();
        $students = Student::orderBy('last_name')->orderBy('first_name')->get();
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();

        return view('grades.create', compact('classes', 'subjects', 'teachers', 'students', 'schoolYears', 'currentYearId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'exam_type' => ['required', 'in:devoir,controle,examen,oral,pratique'],
            'exam_name' => ['required', 'string', 'max:255'],
            'score' => ['nullable', 'numeric', 'min:0', 'lte:max_score'],
            'max_score' => ['required', 'numeric', 'min:1', 'max:100'],
            'exam_date' => ['required', 'date'],
            'semester' => ['required', 'in:1,2'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $grade = Grade::create($validated);

        return redirect()->route('grades.show', $grade)->with('success', 'Note créée avec succès.');
    }

    public function show(Grade $grade)
    {
        $grade->load(['student', 'subject', 'teacher', 'classRoom', 'schoolYear']);
        return view('grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $classes = ClassRoom::orderBy('level')->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->orderBy('first_name')->get();
        $students = Student::orderBy('last_name')->orderBy('first_name')->get();
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();

        return view('grades.edit', compact('grade', 'classes', 'subjects', 'teachers', 'students', 'schoolYears'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'exam_type' => ['required', 'in:devoir,controle,examen,oral,pratique'],
            'exam_name' => ['required', 'string', 'max:255'],
            'score' => ['nullable', 'numeric', 'min:0', 'lte:max_score'],
            'max_score' => ['required', 'numeric', 'min:1', 'max:100'],
            'exam_date' => ['required', 'date'],
            'semester' => ['required', 'in:1,2'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $grade->update($validated);

        return redirect()->route('grades.show', $grade)->with('success', 'Note mise à jour avec succès.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Note supprimée avec succès.');
    }
}
