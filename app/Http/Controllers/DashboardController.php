<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\ClassRoom;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Admin/Director Dashboard
        if ($user->hasRole(['admin', 'director'])) {
            return $this->adminDashboard();
        }
        
        // Teacher Dashboard
        if ($user->hasRole('teacher')) {
            return $this->teacherDashboard();
        }
        
        // Parent Dashboard
        if ($user->hasRole('parent')) {
            return $this->parentDashboard();
        }
        
        // Secretary Dashboard
        if ($user->hasRole('secretary')) {
            return $this->secretaryDashboard();
        }
        
        // Student Dashboard
        if ($user->hasRole('student')) {
            return $this->studentDashboard();
        }
        
        return view('dashboard.default');
    }
    
    private function adminDashboard()
    {
        $currentYear = SchoolYear::current();
        
        // Statistics
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => ClassRoom::active()->count(),
            'total_users' => User::where('is_active', true)->count(),
        ];
        
        // Students by level
        $studentsByLevel = Student::active()
            ->join('enrollments', 'students.id', '=', 'enrollments.student_id')
            ->join('class_rooms', 'enrollments.class_id', '=', 'class_rooms.id')
            ->where('enrollments.school_year_id', $currentYear?->id)
            ->where('enrollments.status', 'active')
            ->select('class_rooms.level', DB::raw('count(*) as count'))
            ->groupBy('class_rooms.level')
            ->get();
        
        // Financial overview
        $financialStats = [
            'total_fees_collected' => Payment::where('status', 'paid')
                ->whereYear('created_at', date('Y'))
                ->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')
                ->sum('amount'),
            'overdue_payments' => Payment::where('status', 'overdue')
                ->sum('amount'),
        ];
        
        // Recent activities
        $recentEnrollments = Student::with(['user', 'currentEnrollment.class'])
            ->latest()
            ->take(5)
            ->get();
        
        // Attendance overview for today
        $todayAttendance = Attendance::whereDate('date', today())
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late
            ')
            ->first();
        
        // Monthly enrollment trends
        $enrollmentTrends = Student::selectRaw('
                MONTH(enrollment_date) as month,
                COUNT(*) as count
            ')
            ->whereYear('enrollment_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('dashboard.admin', compact(
            'stats',
            'studentsByLevel',
            'financialStats',
            'recentEnrollments',
            'todayAttendance',
            'enrollmentTrends'
        ));
    }
    
    private function teacherDashboard()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('profile.edit')
                ->with('error', 'Veuillez compléter votre profil enseignant.');
        }
        
        // Teacher's classes
        $myClasses = $teacher->classes()
            ->with(['students' => function($query) {
                $query->whereHas('currentEnrollment', function($q) {
                    $q->where('status', 'active');
                });
            }])
            ->get();
        
        // Today's schedule
        $todaySchedule = $teacher->timetableEntries()
            ->with(['class', 'subject'])
            ->where('day_of_week', Carbon::now()->dayOfWeek)
            ->orderBy('start_time')
            ->get();
        
        // Pending grades to input
        $pendingGrades = Grade::where('teacher_id', $teacher->id)
            ->whereNull('score')
            ->with(['student.user', 'subject'])
            ->take(10)
            ->get();
        
        // Recent attendance records
        $recentAttendance = Attendance::where('teacher_id', $teacher->id)
            ->with(['student.user', 'class'])
            ->latest()
            ->take(10)
            ->get();
        
        // Statistics
        $stats = [
            'total_students' => $myClasses->sum(function($class) {
                return $class->students->count();
            }),
            'total_classes' => $myClasses->count(),
            'pending_grades' => $pendingGrades->count(),
            'subjects_taught' => $teacher->subjects()->count(),
        ];
        
        return view('dashboard.teacher', compact(
            'teacher',
            'myClasses',
            'todaySchedule',
            'pendingGrades',
            'recentAttendance',
            'stats'
        ));
    }
    
    private function parentDashboard()
    {
        $parent = Auth::user()->parent;
        
        if (!$parent) {
            return redirect()->route('profile.edit')
                ->with('error', 'Veuillez compléter votre profil parent.');
        }
        
        // Parent's children
        $children = $parent->students()
            ->with(['currentEnrollment.class', 'user'])
            ->get();
        
        $childrenData = [];
        
        foreach ($children as $child) {
            // Recent grades
            $recentGrades = Grade::where('student_id', $child->id)
                ->with(['subject', 'teacher.user'])
                ->latest()
                ->take(5)
                ->get();
            
            // Attendance this month
            $attendanceStats = Attendance::where('student_id', $child->id)
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late
                ')
                ->first();
            
            // Pending payments
            $pendingPayments = Payment::where('student_id', $child->id)
                ->where('status', 'pending')
                ->sum('amount');
            
            $childrenData[] = [
                'student' => $child,
                'recent_grades' => $recentGrades,
                'attendance_stats' => $attendanceStats,
                'pending_payments' => $pendingPayments,
            ];
        }
        
        return view('dashboard.parent', compact('parent', 'childrenData'));
    }
    
    private function secretaryDashboard()
    {
        // Similar to admin but focused on administrative tasks
        $stats = [
            'new_enrollments_today' => Student::whereDate('enrollment_date', today())->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
        ];
        
        // Recent enrollments
        $recentEnrollments = Student::with(['user', 'currentEnrollment.class'])
            ->latest()
            ->take(10)
            ->get();
        
        // Payment reminders
        $overduePayments = Payment::where('status', 'overdue')
            ->with(['student.user'])
            ->take(10)
            ->get();
        
        return view('dashboard.secretary', compact(
            'stats',
            'recentEnrollments',
            'overduePayments'
        ));
    }
    
    private function studentDashboard()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('profile.edit')
                ->with('error', 'Veuillez compléter votre profil étudiant.');
        }
        
        // Current enrollment
        $currentEnrollment = $student->currentEnrollment;
        
        // Recent grades
        $recentGrades = Grade::where('student_id', $student->id)
            ->with(['subject', 'teacher.user'])
            ->latest()
            ->take(10)
            ->get();
        
        // Attendance this month
        $attendanceStats = Attendance::where('student_id', $student->id)
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late
            ')
            ->first();
        
        // Today's schedule
        $todaySchedule = [];
        if ($currentEnrollment) {
            $todaySchedule = $currentEnrollment->class->timetableEntries()
                ->with(['teacher.user', 'subject'])
                ->where('day_of_week', Carbon::now()->dayOfWeek)
                ->orderBy('start_time')
                ->get();
        }
        
        // Pending payments
        $pendingPayments = Payment::where('student_id', $student->id)
            ->where('status', 'pending')
            ->with(['fee'])
            ->get();
        
        return view('dashboard.student', compact(
            'student',
            'currentEnrollment',
            'recentGrades',
            'attendanceStats',
            'todaySchedule',
            'pendingPayments'
        ));
    }
}
