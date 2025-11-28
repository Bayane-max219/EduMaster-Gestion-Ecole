<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['school_year_id','type','frequency','mandatory']);

        if (Schema::hasTable('fees')) {
            $query = Fee::orderBy('name');
            if (!empty($filters['school_year_id'])) $query->where('school_year_id', $filters['school_year_id']);
            if (!empty($filters['type'])) $query->where('type', $filters['type']);
            if (!empty($filters['frequency'])) $query->where('frequency', $filters['frequency']);
            if (isset($filters['mandatory']) && $filters['mandatory'] !== '') $query->where('is_mandatory', (bool)$filters['mandatory']);
            $fees = $query->paginate(15)->withQueryString();
        } else {
            $fees = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        $schoolYears = Schema::hasTable('school_years') ? SchoolYear::orderByDesc('start_date')->get() : collect();
        $types = ['inscription','scolarite','cantine','transport','activite','autre'];
        $frequencies = ['unique','mensuel','trimestriel','semestriel','annuel'];

        return view('fees.index', compact('fees','schoolYears','filters','types','frequencies'));
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();
        $types = ['inscription','scolarite','cantine','transport','activite','autre'];
        $frequencies = ['unique','mensuel','trimestriel','semestriel','annuel'];
        return view('fees.create', compact('schoolYears','types','frequencies'));
    }

    public function store(Request $request)
    {
        $types = ['inscription','scolarite','cantine','transport','activite','autre'];
        $frequencies = ['unique','mensuel','trimestriel','semestriel','annuel'];

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'amount' => ['required','numeric','min:0'],
            'type' => ['required','in:'.implode(',', $types)],
            'frequency' => ['required','in:'.implode(',', $frequencies)],
            'class_level' => ['nullable','string','max:100'],
            'is_mandatory' => ['nullable','boolean'],
            'due_date' => ['nullable','date'],
            'school_year_id' => ['required','exists:school_years,id'],
        ]);

        $validated['is_mandatory'] = (bool)($validated['is_mandatory'] ?? true);

        $fee = Fee::create($validated);
        return redirect()->route('fees.show', $fee)->with('success', 'Frais créé avec succès.');
    }

    public function show(Fee $fee)
    {
        $fee->load('schoolYear');
        return view('fees.show', compact('fee'));
    }

    public function edit(Fee $fee)
    {
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();
        $types = ['inscription','scolarite','cantine','transport','activite','autre'];
        $frequencies = ['unique','mensuel','trimestriel','semestriel','annuel'];
        return view('fees.edit', compact('fee','schoolYears','types','frequencies'));
    }

    public function update(Request $request, Fee $fee)
    {
        $types = ['inscription','scolarite','cantine','transport','activite','autre'];
        $frequencies = ['unique','mensuel','trimestriel','semestriel','annuel'];

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'amount' => ['required','numeric','min:0'],
            'type' => ['required','in:'.implode(',', $types)],
            'frequency' => ['required','in:'.implode(',', $frequencies)],
            'class_level' => ['nullable','string','max:100'],
            'is_mandatory' => ['nullable','boolean'],
            'due_date' => ['nullable','date'],
            'school_year_id' => ['required','exists:school_years,id'],
        ]);

        $validated['is_mandatory'] = (bool)($validated['is_mandatory'] ?? true);

        $fee->update($validated);
        return redirect()->route('fees.show', $fee)->with('success', 'Frais mis à jour avec succès.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('fees.index')->with('success', 'Frais supprimé avec succès.');
    }
}
