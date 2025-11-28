<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Note - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center; }
        .page-title { font-size: 2rem; color: #20B2AA; margin-bottom: 1rem; }
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #20B2AA; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
        .btn-primary { background: #20B2AA; color: white; }
        .btn-primary:hover { background: #1a9a92; }
        .btn-secondary { background: #6c757d; color: white; margin-left: 1rem; }
        .btn-secondary:hover { background: #5a6268; }
        .form-actions { display: flex; align-items: center; margin-top: 2rem; }
        .error-message { background: #fee; color: #c33; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .required { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('grades.index') }}">Gestion des Notes</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">➕ Ajouter une Note</h1>
            <p>Renseignez les informations de l'évaluation</p>
        </div>

        <div class="form-container">
            @if ($errors->any())
                <div class="error-message">
                    <strong>Erreurs de validation :</strong>
                    <ul style="margin-top: 0.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('grades.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_id">Élève <span class="required">*</span></label>
                        <select id="student_id" name="student_id" required>
                            <option value="">Sélectionner l'élève</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}" {{ old('student_id')==$st->id?'selected':'' }}>{{ $st->full_name ?? $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="class_id">Classe <span class="required">*</span></label>
                        <select id="class_id" name="class_id" required>
                            <option value="">Sélectionner la classe</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id')==$c->id?'selected':'' }}>{{ $c->name }} ({{ $c->level }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="subject_id">Matière <span class="required">*</span></label>
                        <select id="subject_id" name="subject_id" required>
                            <option value="">Sélectionner la matière</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teacher_id">Professeur <span class="required">*</span></label>
                        <select id="teacher_id" name="teacher_id" required>
                            <option value="">Sélectionner le professeur</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('teacher_id')==$t->id?'selected':'' }}>{{ $t->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="exam_type">Type d'évaluation <span class="required">*</span></label>
                        <select id="exam_type" name="exam_type" required>
                            <option value="">Sélectionner</option>
                            @foreach(['devoir','controle','examen','oral','pratique'] as $type)
                                <option value="{{ $type }}" {{ old('exam_type')==$type?'selected':'' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exam_name">Intitulé <span class="required">*</span></label>
                        <input type="text" id="exam_name" name="exam_name" value="{{ old('exam_name') }}" placeholder="Ex: Contrôle chapitre 2" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="score">Note obtenue</label>
                        <input type="number" id="score" name="score" value="{{ old('score') }}" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="max_score">Note maximale <span class="required">*</span></label>
                        <input type="number" id="max_score" name="max_score" value="{{ old('max_score', 20) }}" step="0.01" min="1" max="100" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="exam_date">Date <span class="required">*</span></label>
                        <input type="date" id="exam_date" name="exam_date" value="{{ old('exam_date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semestre <span class="required">*</span></label>
                        <select id="semester" name="semester" required>
                            <option value="1" {{ old('semester')=='1'?'selected':'' }}>1</option>
                            <option value="2" {{ old('semester')=='2'?'selected':'' }}>2</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="school_year_id">Année scolaire <span class="required">*</span></label>
                        <select id="school_year_id" name="school_year_id" required>
                            @foreach($schoolYears as $y)
                                <option value="{{ $y->id }}" {{ old('school_year_id', $currentYearId)==$y->id?'selected':'' }}>{{ $y->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="notes">Remarques</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Commentaires...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✅ Enregistrer la Note</button>
                    <a href="{{ route('grades.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>
        </div>
    </div>

    @php
        // Mapping élève -> classe (en se basant sur le nom de classe si identique)
        $studentClassMap = [];
        foreach ($students as $st) {
            $classId = null;
            if (!empty($st->classe)) {
                $match = $classes->firstWhere('name', $st->classe);
                if ($match) {
                    $classId = $match->id;
                }
            }
            $studentClassMap[$st->id] = $classId;
        }

        // Mapping professeur <-> matières via la table de pivot teacher_subjects
        $teacherSubjects = [];
        $subjectTeachers = [];

        if (\Illuminate\Support\Facades\Schema::hasTable('teacher_subjects') && \Illuminate\Support\Facades\Schema::hasTable('subjects')) {
            foreach (\App\Models\Teacher::with('subjects:id')->get() as $t) {
                $teacherSubjects[$t->id] = $t->subjects->pluck('id')->toArray();
            }

            foreach ($teacherSubjects as $teacherId => $subjectIds) {
                foreach ($subjectIds as $subjectId) {
                    $subjectTeachers[$subjectId][] = $teacherId;
                }
            }
        }
    @endphp

    <script>
        // Synchroniser automatiquement la classe avec l'élève
        const studentClassMap = JSON.parse('{!! json_encode($studentClassMap) !!}');
        const studentSelect = document.getElementById('student_id');
        const classSelect = document.getElementById('class_id');

        function syncClassWithStudent() {
            if (!studentSelect || !classSelect) return;
            const sid = studentSelect.value;
            const cid = studentClassMap[sid];
            if (cid) {
                classSelect.value = String(cid);
            }
        }

        if (studentSelect && classSelect) {
            studentSelect.addEventListener('change', syncClassWithStudent);
            syncClassWithStudent();
        }

        // Lier professeur et matière via leurs associations
        const teacherSubjects = JSON.parse('{!! json_encode($teacherSubjects) !!}');
        const subjectTeachers = JSON.parse('{!! json_encode($subjectTeachers) !!}');
        const teacherSelect = document.getElementById('teacher_id');
        const subjectSelect = document.getElementById('subject_id');

        const allSubjectOptions = Array.from(subjectSelect.options);
        const allTeacherOptions = Array.from(teacherSelect.options);

        function filterSubjectsForTeacher() {
            if (!teacherSelect) return;
            const tid = teacherSelect.value;
            const allowed = teacherSubjects[tid] || null;
            allSubjectOptions.forEach(opt => {
                if (!opt.value) {
                    opt.disabled = false;
                    return;
                }
                if (!allowed) {
                    opt.disabled = false;
                } else {
                    const id = parseInt(opt.value, 10);
                    opt.disabled = !allowed.includes(id);
                }
            });
        }

        function filterTeachersForSubject() {
            if (!subjectSelect) return;
            const sid = subjectSelect.value;
            const allowed = subjectTeachers[sid] || null;
            allTeacherOptions.forEach(opt => {
                if (!opt.value) {
                    opt.disabled = false;
                    return;
                }
                if (!allowed) {
                    opt.disabled = false;
                } else {
                    const id = parseInt(opt.value, 10);
                    opt.disabled = !allowed.includes(id);
                }
            });
        }

        if (teacherSelect && subjectSelect) {
            teacherSelect.addEventListener('change', filterSubjectsForTeacher);
            subjectSelect.addEventListener('change', filterTeachersForSubject);
            filterSubjectsForTeacher();
            filterTeachersForSubject();
        }
    </script>

</body>
</html>
