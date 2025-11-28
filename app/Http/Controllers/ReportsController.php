<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function payments(Request $request)
    {
        $methods = ['especes','cheque','virement','mobile_money','carte'];
        $statuses = ['pending','paid','partial','overdue','cancelled'];

        $payments = collect();
        $totals = ['count' => 0, 'amount' => 0];
        $byStatus = [];

        $students = Schema::hasTable('students') ? Student::orderBy('first_name')->orderBy('last_name')->orderBy('name')->get(['id','first_name','last_name','name']) : collect();
        $fees = Schema::hasTable('fees') ? Fee::orderBy('name')->get(['id','name','school_year_id']) : collect();
        $schoolYears = Schema::hasTable('school_years') ? DB::table('school_years')->orderBy('name')->get(['id','name']) : collect();

        if (Schema::hasTable('payments')) {
            $base = Payment::query()->with(['student','fee','creator']);

            if ($request->filled('status')) {
                $base->where('status', (string) $request->input('status'));
            }
            if ($request->filled('method')) {
                $base->where('payment_method', (string) $request->input('method'));
            }
            if ($request->filled('student_id')) {
                $base->where('student_id', $request->integer('student_id'));
            }
            if ($request->filled('fee_id')) {
                $base->where('fee_id', $request->integer('fee_id'));
            }
            if ($request->filled('date_from')) {
                $base->whereDate('payment_date', '>=', $request->date('date_from'));
            }
            if ($request->filled('date_to')) {
                $base->whereDate('payment_date', '<=', $request->date('date_to'));
            }
            if ($request->filled('school_year_id')) {
                $base->whereHas('fee', function ($sub) use ($request) {
                    $sub->where('school_year_id', $request->integer('school_year_id'));
                });
            }

            $payments = (clone $base)->latest('payment_date')->paginate(15)->appends($request->query());

            $totals['count'] = (clone $base)->count();
            $totals['amount'] = (clone $base)->sum('amount');

            $grouped = (clone $base)
                ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            foreach ($statuses as $s) {
                $row = $grouped->get($s);
                $byStatus[$s] = [
                    'count' => $row ? (int) $row->count : 0,
                    'total' => $row ? (float) $row->total : 0.0,
                ];
            }
        }

        return view('reports.payments', [
            'payments' => $payments,
            'methods' => $methods,
            'statuses' => $statuses,
            'students' => $students,
            'fees' => $fees,
            'schoolYears' => $schoolYears,
            'totals' => $totals,
            'byStatus' => $byStatus,
            'filters' => [
                'status' => (string) $request->input('status', ''),
                'method' => (string) $request->input('method', ''),
                'student_id' => $request->input('student_id'),
                'fee_id' => $request->input('fee_id'),
                'school_year_id' => $request->input('school_year_id'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ],
        ]);
    }

    public function exportPaymentsCsv(Request $request)
    {
        if (!Schema::hasTable('payments')) {
            return response('payments table missing', 400);
        }

        $q = Payment::query()->with(['student','fee']);

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        if ($request->filled('method')) {
            $q->where('payment_method', $request->string('method'));
        }
        if ($request->filled('student_id')) {
            $q->where('student_id', $request->integer('student_id'));
        }
        if ($request->filled('fee_id')) {
            $q->where('fee_id', $request->integer('fee_id'));
        }
        if ($request->filled('date_from')) {
            $q->whereDate('payment_date', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $q->whereDate('payment_date', '<=', $request->date('date_to'));
        }
        if ($request->filled('school_year_id')) {
            $q->whereHas('fee', function ($sub) use ($request) {
                $sub->where('school_year_id', $request->integer('school_year_id'));
            });
        }

        $rows = $q->orderBy('payment_date')->get();

        $filename = 'payments_report_' . now()->format('Ymd_His') . '.csv';
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date','Élève','Frais','Montant','Méthode','Statut','Référence','N° Reçu']);
            foreach ($rows as $p) {
                $studentName = $p->student->full_name ?? ($p->student->name ?? '');
                $feeName = $p->fee->name ?? '';
                fputcsv($out, [
                    optional($p->payment_date)->format('Y-m-d'),
                    $studentName,
                    $feeName,
                    number_format((float)$p->amount, 2, '.', ''),
                    $p->payment_method,
                    $p->status,
                    $p->reference,
                    $p->receipt_number,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function studentsPdf()
    {
        $has = Schema::hasTable('students');
        $total = $has ? DB::table('students')->count() : 0;
        $byClass = $has ? DB::table('students')->select('classe', DB::raw('COUNT(*) as count'))->groupBy('classe')->orderBy('classe')->get() : collect();
        $byGender = ($has && Schema::hasColumn('students','genre'))
            ? DB::table('students')->select('genre', DB::raw('COUNT(*) as count'))->groupBy('genre')->get()
            : collect();
        return view('reports.students_pdf', compact('total','byClass','byGender','has'));
    }

    public function studentsCharts()
    {
        $has = Schema::hasTable('students');
        $byClass = $has ? DB::table('students')->select('classe', DB::raw('COUNT(*) as count'))->groupBy('classe')->orderBy('classe')->get() : collect();
        $labels = $byClass->pluck('classe')->map(function($v){ return $v ?: 'Sans classe'; })->values();
        $data = $byClass->pluck('count')->map(fn($v)=>(int)$v)->values();
        $gender = ($has && Schema::hasColumn('students','genre')) ? DB::table('students')->select('genre', DB::raw('COUNT(*) as count'))->groupBy('genre')->get() : collect();
        $genderLabels = $gender->pluck('genre')->map(function($v){ return $v ?: 'Inconnu'; })->values();
        $genderData = $gender->pluck('count')->map(fn($v)=>(int)$v)->values();
        return view('reports.students_charts', compact('labels','data','genderLabels','genderData','has'));
    }

    public function gradesPdf()
    {
        $has = Schema::hasTable('grades');
        $total = $has ? DB::table('grades')->count() : 0;
        $avg = $has ? round((float) DB::table('grades')->avg('score'), 2) : 0.0;
        $bySubject = ($has && Schema::hasTable('subjects'))
            ? DB::table('grades')->select('subject_id', DB::raw('COUNT(*) as count'))
                ->groupBy('subject_id')->get()
            : collect();
        $subjects = Schema::hasTable('subjects') ? DB::table('subjects')->pluck('name','id') : collect();
        return view('reports.grades_pdf', [
            'has' => $has,
            'total' => $total,
            'avg' => $avg,
            'bySubject' => $bySubject,
            'subjects' => $subjects,
        ]);
    }

    public function gradesCharts()
    {
        $has = Schema::hasTable('grades');
        $bySubject = ($has && Schema::hasTable('subjects'))
            ? DB::table('grades')->select('subject_id', DB::raw('COUNT(*) as count'))->groupBy('subject_id')->get()
            : collect();
        $subjects = Schema::hasTable('subjects') ? DB::table('subjects')->pluck('name','id') : collect();
        $labels = $bySubject->map(function($row) use ($subjects){ return $subjects[$row->subject_id] ?? ('Matière #'.$row->subject_id); });
        $data = $bySubject->pluck('count')->map(fn($v)=>(int)$v)->values();
        return view('reports.grades_charts', compact('labels','data','has'));
    }

    public function attendancesPdf()
    {
        $has = Schema::hasTable('attendances');
        $total = $has ? DB::table('attendances')->count() : 0;
        $byStatus = $has && Schema::hasColumn('attendances','status')
            ? DB::table('attendances')->select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->get()
            : collect();
        return view('reports.attendances_pdf', compact('has','total','byStatus'));
    }

    public function attendancesCharts()
    {
        $has = Schema::hasTable('attendances');
        $byStatus = $has && Schema::hasColumn('attendances','status')
            ? DB::table('attendances')->select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->get()
            : collect();
        $labels = $byStatus->pluck('status')->map(fn($v)=>$v ?: 'Inconnu');
        $data = $byStatus->pluck('count')->map(fn($v)=>(int)$v);
        return view('reports.attendances_charts', compact('labels','data','has'));
    }

    public function teachersPdf()
    {
        $has = Schema::hasTable('teachers');
        $total = $has ? DB::table('teachers')->count() : 0;
        $byStatus = $has && Schema::hasColumn('teachers','status')
            ? DB::table('teachers')->select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->get()
            : collect();
        $bySpec = $has && Schema::hasColumn('teachers','specialization')
            ? DB::table('teachers')->select('specialization', DB::raw('COUNT(*) as count'))->groupBy('specialization')->orderBy('specialization')->get()
            : collect();
        return view('reports.teachers_pdf', compact('has','total','byStatus','bySpec'));
    }

    public function teachersCharts()
    {
        $has = Schema::hasTable('teachers');
        $byStatus = $has && Schema::hasColumn('teachers','status')
            ? DB::table('teachers')->select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->get()
            : collect();
        $statusLabels = $byStatus->pluck('status')->map(fn($v)=>$v ?: 'Inconnu');
        $statusData = $byStatus->pluck('count')->map(fn($v)=>(int)$v);
        $bySpec = $has && Schema::hasColumn('teachers','specialization')
            ? DB::table('teachers')->select('specialization', DB::raw('COUNT(*) as count'))->groupBy('specialization')->orderBy('specialization')->get()
            : collect();
        $specLabels = $bySpec->pluck('specialization')->map(fn($v)=>$v ?: 'Non défini');
        $specData = $bySpec->pluck('count')->map(fn($v)=>(int)$v);
        return view('reports.teachers_charts', compact('statusLabels','statusData','specLabels','specData','has'));
    }

    public function executivePdf()
    {
        $studentsCount = Schema::hasTable('students') ? (int) DB::table('students')->count() : 0;
        $teachersCount = Schema::hasTable('teachers') ? (int) DB::table('teachers')->count() : 0;
        $paymentsTotal = Schema::hasTable('payments') ? (float) DB::table('payments')->sum('amount') : 0.0;
        $feesCount = Schema::hasTable('fees') ? (int) DB::table('fees')->count() : 0;
        return view('reports.executive_pdf', compact('studentsCount','teachersCount','paymentsTotal','feesCount'));
    }

    public function executiveCharts()
    {
        $studentsCount = Schema::hasTable('students') ? (int) DB::table('students')->count() : 0;
        $teachersCount = Schema::hasTable('teachers') ? (int) DB::table('teachers')->count() : 0;
        $paymentsTotal = Schema::hasTable('payments') ? (float) DB::table('payments')->sum('amount') : 0.0;
        $feesCount = Schema::hasTable('fees') ? (int) DB::table('fees')->count() : 0;
        return view('reports.executive_charts', compact('studentsCount','teachersCount','paymentsTotal','feesCount'));
    }
}
