<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer tous les professeurs
        $query = Teacher::query();
        
        // Recherche par nom si spécifié
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }
        
        // Filtrer par statut si spécifié
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filtrer par spécialisation si spécifié
        if ($request->filled('specialization') && $request->specialization !== 'all') {
            $query->where('specialization', $request->specialization);
        }
        
        $teachers = $query->orderBy('first_name')->paginate(10);
        
        return view('teachers.index', compact('teachers'));
    }
    
    public function create()
    {
        return view('teachers.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:teachers',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'qualification' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        Teacher::create([
            'user_id' => null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'status' => 'active',
            'teacher_number' => 'PROF' . date('Y') . str_pad(Teacher::count() + 1, 4, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('teachers.index')->with('success', 'Professeur créé avec succès.');
    }
    
    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }
    
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }
    
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'qualification' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'status' => $request->status,
        ];
        
        $teacher->update($updateData);

        return redirect()->route('teachers.index')->with('success', 'Professeur mis à jour avec succès.');
    }
    
    public function destroy(Teacher $teacher)
    {
        try {
            $teacherName = $teacher->full_name;
            $teacherId = $teacher->id;
            
            // Suppression directe avec DB
            $deleted = DB::table('teachers')->where('id', $teacherId)->delete();
            
            if ($deleted) {
                return redirect()->route('teachers.index')->with('success', "Professeur {$teacherName} (ID: {$teacherId}) supprimé avec succès.");
            } else {
                return redirect()->route('teachers.index')->with('error', 'Aucune ligne supprimée.');
            }
        } catch (\Exception $e) {
            return redirect()->route('teachers.index')->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
