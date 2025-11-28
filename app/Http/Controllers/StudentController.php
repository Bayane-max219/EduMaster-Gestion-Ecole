<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer tous les élèves
        $query = Student::query();
        
        // Recherche par nom si spécifié
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filtrage par classe si spécifié
        if ($request->filled('classe') && $request->classe !== 'all') {
            $query->where('classe', $request->classe);
        }
        
        // Filtrage par statut si spécifié
        if ($request->filled('statut') && $request->statut !== 'all') {
            $active = $request->statut === 'actif';
            $query->where('is_active', $active);
        }
        
        $students = $query->orderBy('name')->paginate(10);
        
        return view('students.index', compact('students'));
    }
    
    public function create()
    {
        return view('students.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:students',
            'phone' => 'nullable|string|max:20',
            'classe' => 'required|string',
            'date_naissance' => 'required|date',
            'parent_tuteur' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'genre' => 'required|in:masculin,feminin',
        ]);

        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'classe' => $request->classe,
            'date_naissance' => $request->date_naissance,
            'parent_tuteur' => $request->parent_tuteur,
            'adresse' => $request->adresse,
            'genre' => $request->genre,
            'is_active' => true,
            'student_number' => 'STU' . date('Y') . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT),
            // Valeurs par défaut pour les champs obligatoires de l'ancienne structure
            'first_name' => explode(' ', $request->name)[0] ?? $request->name,
            'last_name' => explode(' ', $request->name)[1] ?? '',
            'date_of_birth' => $request->date_naissance,
            'gender' => $request->genre == 'masculin' ? 'male' : 'female',
            'address' => $request->adresse,
            'status' => 'active',
        ]);

        return redirect()->route('students.index')->with('success', 'Élève créé avec succès.');
    }
    
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }
    
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }
    
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'classe' => 'required|string',
            'date_naissance' => 'required|date',
            'parent_tuteur' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'genre' => 'required|in:masculin,feminin',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'classe' => $request->classe,
            'date_naissance' => $request->date_naissance,
            'parent_tuteur' => $request->parent_tuteur,
            'adresse' => $request->adresse,
            'genre' => $request->genre,
            // Mettre à jour aussi les champs de l'ancienne structure
            'first_name' => explode(' ', $request->name)[0] ?? $request->name,
            'last_name' => explode(' ', $request->name)[1] ?? '',
            'date_of_birth' => $request->date_naissance,
            'gender' => $request->genre == 'masculin' ? 'male' : 'female',
            'address' => $request->adresse,
        ];
        
        $student->update($updateData);

        return redirect()->route('students.index')->with('success', 'Élève mis à jour avec succès.');
    }
    
    public function destroy(Student $student)
    {
        try {
            $studentName = $student->name;
            $studentId = $student->id;
            
            // Suppression directe avec DB
            $deleted = DB::table('students')->where('id', $studentId)->delete();
            
            if ($deleted) {
                return redirect()->route('students.index')->with('success', "Élève {$studentName} (ID: {$studentId}) supprimé avec succès.");
            } else {
                return redirect()->route('students.index')->with('error', 'Aucune ligne supprimée.');
            }
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
