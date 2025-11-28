<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Paiement - EduMaster</title>
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
        .helper { font-size: .9rem; color:#666; margin-top:.25rem; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('payments.index') }}">Gestion des Paiements</a>
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
            <h1 class="page-title">➕ Nouveau Paiement</h1>
            <p>Renseignez les informations du paiement</p>
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

            <form method="POST" action="{{ route('payments.store') }}">
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
                        <div class="helper" id="student-class-display">Classe actuelle : —</div>
                    </div>
                    <div class="form-group">
                        <label for="fee_id">Frais <span class="required">*</span></label>
                        <select id="fee_id" name="fee_id" required>
                            <option value="">Sélectionner le frais</option>
                            @foreach($fees as $f)
                                <option data-amount="{{ $f->amount }}" value="{{ $f->id }}" {{ old('fee_id')==$f->id?'selected':'' }}>{{ $f->name }} - {{ number_format((float)$f->amount, 0, ',', ' ') }} Ar</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="amount">Montant <span class="required">*</span></label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required>
                        <div class="helper">Par défaut, le montant du frais sera suggéré.</div>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Date de paiement <span class="required">*</span></label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', $today) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_method">Méthode <span class="required">*</span></label>
                        <select id="payment_method" name="payment_method" required>
                            @foreach($methods as $m)
                                <option value="{{ $m }}" {{ old('payment_method')==$m?'selected':'' }}>{{ ucwords(str_replace('_',' ', $m)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Statut <span class="required">*</span></label>
                        @php
                            $statusLabels = [
                                'pending' => 'En attente',
                                'paid' => 'Payé',
                                'partial' => 'Partiel',
                                'overdue' => 'En retard',
                                'cancelled' => 'Annulé',
                            ];
                        @endphp
                        <select id="status" name="status" required>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status','paid')==$s?'selected':'' }}>{{ $statusLabels[$s] ?? ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="reference">Référence</label>
                        <input type="text" id="reference" name="reference" value="{{ old('reference') }}" placeholder="Ref. chèque / virement ...">
                    </div>
                    <div class="form-group">
                        <label for="receipt_number">N° Reçu</label>
                        <input type="text" id="receipt_number" name="receipt_number" value="{{ old('receipt_number') }}" placeholder="Ex: R-2025-00123">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="notes">Remarques</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Commentaires...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✅ Enregistrer le Paiement</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>
        </div>
    </div>

    @php
        $studentClasses = $students->mapWithKeys(function($st) {
            return [$st->id => $st->classe ?? ''];
        });
    @endphp

    <script>
        // Suggérer automatiquement le montant selon le frais choisi
        const feeSelect = document.getElementById('fee_id');
        const amountInput = document.getElementById('amount');
        if (feeSelect && amountInput) {
            feeSelect.addEventListener('change', function () {
                const opt = this.options[this.selectedIndex];
                const amt = opt ? opt.getAttribute('data-amount') : '';
                if (amt) amountInput.value = amt;
            });
        }

        // Afficher automatiquement la classe de l'élève sélectionné
        const studentSelect = document.getElementById('student_id');
        const studentClassDisplay = document.getElementById('student-class-display');
        const studentClasses = JSON.parse('{!! json_encode($studentClasses) !!}');

        function refreshStudentClass() {
            if (!studentSelect || !studentClassDisplay) return;
            const id = studentSelect.value;
            const classe = studentClasses[id] || '';
            studentClassDisplay.textContent = classe
                ? 'Classe actuelle : ' + classe
                : 'Classe actuelle : —';
        }

        if (studentSelect && studentClassDisplay) {
            studentSelect.addEventListener('change', refreshStudentClass);
            refreshStudentClass();
        }
    </script>
</body>
</html>
