<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Fee;
use App\Models\Student;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['student_id','status','payment_method','school_year_id','date_from','date_to']);

        if (Schema::hasTable('payments')) {
            // Base query with filters (without eager loading) to réutiliser for stats
            $baseQuery = Payment::query();

            if (!empty($filters['student_id'])) $baseQuery->where('student_id', $filters['student_id']);
            if (!empty($filters['status'])) $baseQuery->where('status', $filters['status']);
            if (!empty($filters['payment_method'])) $baseQuery->where('payment_method', $filters['payment_method']);
            if (!empty($filters['date_from'])) $baseQuery->whereDate('payment_date', '>=', $filters['date_from']);
            if (!empty($filters['date_to'])) $baseQuery->whereDate('payment_date', '<=', $filters['date_to']);
            if (!empty($filters['school_year_id'])) {
                $baseQuery->whereHas('fee', function($q) use ($filters) {
                    $q->where('school_year_id', $filters['school_year_id']);
                });
            }

            // Liste paginée avec relations
            $payments = (clone $baseQuery)
                ->with(['student','fee','creator'])
                ->orderByDesc('payment_date')
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();

            // Statistiques (basées sur le même filtre)
            $statsBase = clone $baseQuery;
            $stats = [
                'month_total'    => (clone $statsBase)
                    ->whereBetween('payment_date', [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum('amount'),
                'received_count' => (clone $statsBase)->where('status', 'paid')->count(),
                'pending_count'  => (clone $statsBase)->where('status', 'pending')->count(),
                'overdue_count'  => (clone $statsBase)->where('status', 'overdue')->count(),
            ];
        } else {
            $payments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
            $stats = [
                'month_total' => 0,
                'received_count' => 0,
                'pending_count' => 0,
                'overdue_count' => 0,
            ];
        }

        $students = collect();
        if (Schema::hasTable('students')) {
            if (Schema::hasColumn('students', 'last_name') && Schema::hasColumn('students', 'first_name')) {
                $students = Student::orderBy('last_name')->orderBy('first_name')->get();
            } else {
                $students = Student::orderBy('name')->get();
            }
        }
        $schoolYears = Schema::hasTable('school_years') ? SchoolYear::orderByDesc('start_date')->get() : collect();

        $methods = ['especes','cheque','virement','mobile_money','carte'];
        $statuses = ['pending','paid','partial','overdue','cancelled'];

        return view('payments.index', compact('payments','students','schoolYears','filters','methods','statuses','stats'));
    }

    public function create()
    {
        // Students list (tolerant to table/columns absence)
        $students = collect();
        if (Schema::hasTable('students')) {
            if (Schema::hasColumn('students', 'last_name') && Schema::hasColumn('students', 'first_name')) {
                $students = Student::orderBy('last_name')->orderBy('first_name')->get();
            } else {
                $students = Student::orderBy('name')->get();
            }
        }

        // Fees list (tolerant to table absence)
        $fees = Schema::hasTable('fees') ? Fee::orderBy('name')->get() : collect();
        $methods = ['especes','cheque','virement','mobile_money','carte'];
        $statuses = ['pending','paid','partial','overdue','cancelled'];
        $today = now()->format('Y-m-d');

        return view('payments.create', compact('students','fees','methods','statuses','today'));
    }

    public function store(Request $request)
    {
        $methods = ['especes','cheque','virement','mobile_money','carte'];
        $statuses = ['pending','paid','partial','overdue','cancelled'];

        $validated = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'fee_id' => ['required','exists:fees,id'],
            'amount' => ['required','numeric','min:0'],
            'payment_date' => ['required','date'],
            'payment_method' => ['required','in:'.implode(',', $methods)],
            'status' => ['required','in:'.implode(',', $statuses)],
            'reference' => ['nullable','string','max:255'],
            'receipt_number' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);

        // Snapshot de la classe de l'élève au moment du paiement
        $student = Student::find($validated['student_id']);
        $validated['classe'] = $student->classe ?? null;

        // Optional business rule: amount cannot exceed fee amount
        $fee = Fee::find($validated['fee_id']);
        if ($fee && $validated['amount'] > $fee->amount) {
            return back()->withErrors(['amount' => 'Le montant ne peut pas dépasser le montant du frais ('.$fee->amount.').'])->withInput();
        }

        $validated['created_by'] = Auth::id();

        $payment = Payment::create($validated);

        return redirect()->route('payments.show', $payment)->with('success', 'Paiement créé avec succès.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student','fee','creator']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        // Students list (tolerant)
        if (Schema::hasTable('students')) {
            if (Schema::hasColumn('students', 'last_name') && Schema::hasColumn('students', 'first_name')) {
                $students = Student::orderBy('last_name')->orderBy('first_name')->get();
            } else {
                $students = Student::orderBy('name')->get();
            }
        } else {
            $students = collect();
        }

        // Fees list (tolerant)
        $fees = Schema::hasTable('fees') ? Fee::orderBy('name')->get() : collect();
        $methods = ['especes','cheque','virement','mobile_money','carte'];
        $statuses = ['pending','paid','partial','overdue','cancelled'];

        return view('payments.edit', compact('payment','students','fees','methods','statuses'));
    }

    public function update(Request $request, Payment $payment)
    {
        $methods = ['especes','cheque','virement','mobile_money','carte'];
        $statuses = ['pending','paid','partial','overdue','cancelled'];

        $validated = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'fee_id' => ['required','exists:fees,id'],
            'amount' => ['required','numeric','min:0'],
            'payment_date' => ['required','date'],
            'payment_method' => ['required','in:'.implode(',', $methods)],
            'status' => ['required','in:'.implode(',', $statuses)],
            'reference' => ['nullable','string','max:255'],
            'receipt_number' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);

        // Mettre à jour la classe snapshot à partir de l'élève sélectionné
        $student = Student::find($validated['student_id']);
        $validated['classe'] = $student->classe ?? null;

        $fee = Fee::find($validated['fee_id']);
        if ($fee && $validated['amount'] > $fee->amount) {
            return back()->withErrors(['amount' => 'Le montant ne peut pas dépasser le montant du frais ('.$fee->amount.').'])->withInput();
        }

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Paiement supprimé avec succès.');
    }
}
