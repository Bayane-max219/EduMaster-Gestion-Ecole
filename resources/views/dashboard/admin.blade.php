@extends('layouts.app')

@section('title', 'Tableau de Bord Administrateur')
@section('page-title', 'Tableau de Bord Administrateur')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Élèves</p>
                    <p class="text-3xl font-bold text-primary">{{ $stats['total_students'] }}</p>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-graduate text-primary text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up"></i>
                    +12% ce mois
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Professeurs</p>
                    <p class="text-3xl font-bold text-primary">{{ $stats['total_teachers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-primary text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600">
                    <i class="fas fa-arrow-right"></i>
                    Stable
                </span>
            </div>
        </div>

        <div class="stat-card secondary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Classes</p>
                    <p class="text-3xl font-bold text-secondary">{{ $stats['total_classes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-secondary bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-door-open text-secondary text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up"></i>
                    +2 nouvelles
                </span>
            </div>
        </div>

        <div class="stat-card secondary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilisateurs Actifs</p>
                    <p class="text-3xl font-bold text-secondary">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-secondary bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-secondary text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up"></i>
                    +8% ce mois
                </span>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aperçu Financier</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Frais Collectés</span>
                    <span class="font-semibold text-green-600">{{ number_format($financialStats['total_fees_collected'], 0, ',', ' ') }} Ar</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Paiements Pendants</span>
                    <span class="font-semibold text-yellow-600">{{ number_format($financialStats['pending_payments'], 0, ',', ' ') }} Ar</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Paiements en Retard</span>
                    <span class="font-semibold text-red-600">{{ number_format($financialStats['overdue_payments'], 0, ',', ' ') }} Ar</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Présences Aujourd'hui</h3>
            @if($todayAttendance)
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total</span>
                        <span class="font-semibold">{{ $todayAttendance->total }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600">Présents</span>
                        <span class="font-semibold text-green-600">{{ $todayAttendance->present }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-red-600">Absents</span>
                        <span class="font-semibold text-red-600">{{ $todayAttendance->absent }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-600">En Retard</span>
                        <span class="font-semibold text-yellow-600">{{ $todayAttendance->late }}</span>
                    </div>
                </div>
                
                <!-- Attendance Chart -->
                <div class="mt-4">
                    <canvas id="attendanceChart" width="200" height="200"></canvas>
                </div>
            @else
                <p class="text-gray-500">Aucune donnée de présence pour aujourd'hui</p>
            @endif
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Élèves par Niveau</h3>
            <div class="space-y-3">
                @foreach($studentsByLevel as $level)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $level->level }}</span>
                        <span class="font-semibold text-primary">{{ $level->count }}</span>
                    </div>
                @endforeach
            </div>
            
            <!-- Students by Level Chart -->
            <div class="mt-4">
                <canvas id="studentsChart" width="200" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Inscriptions Récentes</h3>
            <div class="space-y-3">
                @forelse($recentEnrollments as $student)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-graduate text-primary"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $student->user->name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $student->currentEnrollment->class->name ?? 'Non assigné' }}
                                </p>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">
                            {{ $student->enrollment_date->format('d/m/Y') }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500">Aucune inscription récente</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tendances d'Inscription Mensuelle</h3>
            <canvas id="enrollmentTrendsChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('students.create') }}" class="btn-primary text-center">
                <i class="fas fa-user-plus mr-2"></i>
                Nouvel Élève
            </a>
            <a href="{{ route('teachers.create') }}" class="btn-secondary text-center">
                <i class="fas fa-user-tie mr-2"></i>
                Nouveau Professeur
            </a>
            <a href="{{ route('classes.create') }}" class="btn-primary text-center">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle Classe
            </a>
            <a href="{{ route('reports.index') }}" class="btn-secondary text-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Voir Rapports
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Attendance Chart
    @if($todayAttendance)
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Présents', 'Absents', 'En Retard'],
            datasets: [{
                data: [{{ $todayAttendance->present }}, {{ $todayAttendance->absent }}, {{ $todayAttendance->late }}],
                backgroundColor: ['#10B981', '#EF4444', '#F59E0B'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
    @endif

    // Students by Level Chart
    const studentsCtx = document.getElementById('studentsChart').getContext('2d');
    new Chart(studentsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($studentsByLevel->pluck('level')) !!},
            datasets: [{
                label: 'Nombre d\'élèves',
                data: {!! json_encode($studentsByLevel->pluck('count')) !!},
                backgroundColor: '#20B2AA',
                borderColor: '#1A9B94',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Enrollment Trends Chart
    const trendsCtx = document.getElementById('enrollmentTrendsChart').getContext('2d');
    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($enrollmentTrends->pluck('month')->map(function($month) { return ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'][$month - 1]; })) !!},
            datasets: [{
                label: 'Inscriptions',
                data: {!! json_encode($enrollmentTrends->pluck('count')) !!},
                borderColor: '#FF8C42',
                backgroundColor: 'rgba(255, 140, 66, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
