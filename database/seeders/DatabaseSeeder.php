<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SchoolYear;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $roles = [
            'admin' => 'Administrateur',
            'director' => 'Directeur/Proviseur', 
            'secretary' => 'Secrétaire',
            'teacher' => 'Professeur',
            'parent' => 'Parent',
            'student' => 'Élève'
        ];

        foreach ($roles as $name => $description) {
            Role::create([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }

        // Create Permissions
        $permissions = [
            // Users Management
            'users.view', 'users.create', 'users.edit', 'users.delete',
            
            // Students Management
            'students.view', 'students.create', 'students.edit', 'students.delete',
            'students.enroll', 'students.export',
            
            // Teachers Management
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.delete',
            'teachers.assign',
            
            // Classes Management
            'classes.view', 'classes.create', 'classes.edit', 'classes.delete',
            
            // Subjects Management
            'subjects.view', 'subjects.create', 'subjects.edit', 'subjects.delete',
            
            // Grades Management
            'grades.view', 'grades.create', 'grades.edit', 'grades.delete',
            'grades.bulk', 'bulletins.generate',
            
            // Attendance Management
            'attendances.view', 'attendances.create', 'attendances.edit', 'attendances.delete',
            'attendances.bulk',
            
            // Payments Management
            'payments.view', 'payments.create', 'payments.edit', 'payments.delete',
            'payments.receipts', 'payments.reports',
            
            // Timetables Management
            'timetables.view', 'timetables.create', 'timetables.edit', 'timetables.delete',
            'timetables.generate',
            
            // Reports
            'reports.view', 'reports.financial', 'reports.academic', 'reports.attendance',
            
            // System
            'system.settings', 'system.logs', 'system.backup'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign Permissions to Roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        $directorRole = Role::findByName('director');
        $directorRole->givePermissionTo([
            'students.view', 'students.create', 'students.edit', 'students.export',
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.assign',
            'classes.view', 'classes.create', 'classes.edit',
            'subjects.view', 'subjects.create', 'subjects.edit',
            'grades.view', 'bulletins.generate',
            'attendances.view', 'payments.view', 'payments.reports',
            'timetables.view', 'timetables.create', 'timetables.edit',
            'reports.view', 'reports.financial', 'reports.academic', 'reports.attendance'
        ]);

        $secretaryRole = Role::findByName('secretary');
        $secretaryRole->givePermissionTo([
            'students.view', 'students.create', 'students.edit', 'students.enroll',
            'classes.view', 'attendances.view', 'attendances.create', 'attendances.edit',
            'payments.view', 'payments.create', 'payments.edit', 'payments.receipts',
            'timetables.view'
        ]);

        $teacherRole = Role::findByName('teacher');
        $teacherRole->givePermissionTo([
            'students.view', 'grades.view', 'grades.create', 'grades.edit', 'grades.bulk',
            'attendances.view', 'attendances.create', 'attendances.edit', 'attendances.bulk',
            'timetables.view'
        ]);

        $parentRole = Role::findByName('parent');
        $parentRole->givePermissionTo([
            'students.view', 'grades.view', 'attendances.view', 'payments.view'
        ]);

        // Create Default Users
        $admin = User::create([
            'name' => 'Administrateur EduMaster',
            'email' => 'admin@edumaster.mg',
            'password' => Hash::make('password'),
            'phone' => '+261 34 00 000 01',
            'address' => 'Antananarivo, Madagascar',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $director = User::create([
            'name' => 'Directeur École',
            'email' => 'director@edumaster.mg',
            'password' => Hash::make('password'),
            'phone' => '+261 34 00 000 02',
            'address' => 'Antananarivo, Madagascar',
            'is_active' => true,
        ]);
        $director->assignRole('director');

        $secretary = User::create([
            'name' => 'Secrétaire École',
            'email' => 'secretary@edumaster.mg',
            'password' => Hash::make('password'),
            'phone' => '+261 34 00 000 03',
            'address' => 'Antananarivo, Madagascar',
            'is_active' => true,
        ]);
        $secretary->assignRole('secretary');

        $teacher = User::create([
            'name' => 'Professeur Rakoto',
            'email' => 'teacher@edumaster.mg',
            'password' => Hash::make('password'),
            'phone' => '+261 34 00 000 04',
            'address' => 'Antananarivo, Madagascar',
            'is_active' => true,
        ]);
        $teacher->assignRole('teacher');

        $parent = User::create([
            'name' => 'Parent Rabe',
            'email' => 'parent@edumaster.mg',
            'password' => Hash::make('password'),
            'phone' => '+261 34 00 000 05',
            'address' => 'Antananarivo, Madagascar',
            'is_active' => true,
        ]);
        $parent->assignRole('parent');

        // Create Current School Year
        SchoolYear::create([
            'name' => '2024-2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-07-31',
            'is_current' => true,
            'status' => 'active',
            'description' => 'Année scolaire 2024-2025'
        ]);

        // Create Previous School Year
        SchoolYear::create([
            'name' => '2023-2024',
            'start_date' => '2023-09-01',
            'end_date' => '2024-07-31',
            'is_current' => false,
            'status' => 'archived',
            'description' => 'Année scolaire 2023-2024'
        ]);

        $this->command->info('✅ Base de données initialisée avec succès !');
        $this->command->info('📧 Comptes créés :');
        $this->command->info('   Admin: admin@edumaster.mg / password');
        $this->command->info('   Directeur: director@edumaster.mg / password');
        $this->command->info('   Secrétaire: secretary@edumaster.mg / password');
        $this->command->info('   Professeur: teacher@edumaster.mg / password');
        $this->command->info('   Parent: parent@edumaster.mg / password');
    }
}
