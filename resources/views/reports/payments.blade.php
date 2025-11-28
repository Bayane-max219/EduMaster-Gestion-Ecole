<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Paiements - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 1.25rem 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 1rem; display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap; gap: .75rem; }
        .page-title { font-size: 1.6rem; color: #20B2AA; }
        .btn { padding: 0.6rem 1rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.3s; display:inline-block; }
        .btn:hover { background: #1a9a92; }
        .btn-secondary { background:#6c757d; }
        .btn-warning { background:#ffc107; color:#222; }
        .btn-light { background:#e9ecef; color:#222; }
        .filters { background:white; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; margin-bottom:1rem; }
        .filters-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: .75rem; }
        .label { display:block; color:#555; font-weight:600; margin-bottom:.35rem; }
        select, input { width:100%; padding:.5rem; border:2px solid #e1e5e9; border-radius:8px; background:white; }
        .stats { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: .75rem; margin-bottom:1rem; }
        .stat { background:white; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; }
        .stat h3 { color:#20B2AA; font-size: .95rem; margin-bottom:.35rem; }
        .stat .val { font-size:1.25rem; font-weight:700; }
        .table-card { background:white; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.08); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:.75rem 1rem; border-bottom:1px solid #eee; text-align:left; }
        th { background:#f8f9fa; color:#333; font-weight:700; }
        .amount { font-weight:700; color:#20B2AA; }
        .badge { padding: .2rem .5rem; border-radius: 9999px; font-size:.8rem; font-weight:700; }
        .status-paid { background:#d4edda; color:#155724; }
        .status-pending { background:#fff3cd; color:#856404; }
        .status-overdue { background:#f8d7da; color:#721c24; }
        .toolbar { display:flex; gap:.5rem; flex-wrap:wrap; }
        @media print {
            .header, .filters, .toolbar, .page-actions, .pagination { display:none !important; }
            body { background:white; }
            .container { margin:0; padding:0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('reports.index') }}">Rapports</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">🚪 Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">💰 Rapport des Paiements</h1>
            <div class="page-actions toolbar">
                <a href="{{ route('reports.payments.export', request()->query()) }}" class="btn btn-light">⬇️ Export CSV</a>
                <button onclick="window.print()" class="btn btn-secondary">🖨️ Imprimer</button>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.payments') }}" class="filters">
            <div class="filters-grid">
                <div>
                    <label class="label">Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        @foreach(($statuses ?? []) as $s)
                            <option value="{{ $s }}" {{ ($filters['status'] ?? '')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Méthode</label>
                    <select name="method">
                        <option value="">Toutes</option>
                        @foreach(($methods ?? []) as $m)
                            <option value="{{ $m }}" {{ ($filters['method'] ?? '')===$m ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ', $m)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Élève</label>
                    <select name="student_id">
                        <option value="">Tous</option>
                        @foreach(($students ?? []) as $st)
                            <option value="{{ $st->id }}" {{ (string)($filters['student_id'] ?? '') === (string)$st->id ? 'selected' : '' }}>
                                {{ $st->first_name ? ($st->first_name.' '.$st->last_name) : $st->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Frais</label>
                    <select name="fee_id">
                        <option value="">Tous</option>
                        @foreach(($fees ?? []) as $f)
                            <option value="{{ $f->id }}" {{ (string)($filters['fee_id'] ?? '') === (string)$f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Année scolaire</label>
                    <select name="school_year_id">
                        <option value="">Toutes</option>
                        @foreach(($schoolYears ?? []) as $y)
                            <option value="{{ $y->id }}" {{ (string)($filters['school_year_id'] ?? '') === (string)$y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Date de début</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div>
                    <label class="label">Date de fin</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div style="display:flex; align-items:flex-end; gap:.5rem;">
                    <button type="submit" class="btn">Filtrer</button>
                    <a href="{{ route('reports.payments') }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </div>
        </form>

        <div class="stats">
            <div class="stat">
                <h3>Total paiements</h3>
                <div class="val">{{ number_format((int)($totals['count'] ?? 0), 0, ',', ' ') }}</div>
            </div>
            <div class="stat">
                <h3>Montant total</h3>
                <div class="val">{{ number_format((float)($totals['amount'] ?? 0), 0, ',', ' ') }} Ar</div>
            </div>
            <div class="stat">
                <h3>Payés</h3>
                <div class="val"><span class="badge status-paid">{{ number_format((int)($byStatus['paid']['count'] ?? 0), 0, ',', ' ') }}</span></div>
            </div>
            <div class="stat">
                <h3>En attente</h3>
                <div class="val"><span class="badge status-pending">{{ number_format((int)($byStatus['pending']['count'] ?? 0), 0, ',', ' ') }}</span></div>
            </div>
            <div class="stat">
                <h3>En retard</h3>
                <div class="val"><span class="badge status-overdue">{{ number_format((int)($byStatus['overdue']['count'] ?? 0), 0, ',', ' ') }}</span></div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Frais</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th>Réf</th>
                        <th>N° Reçu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($payments ?? []) as $p)
                        @php
                            $statusClass = match($p->status) {
                                'paid' => 'status-paid',
                                'overdue' => 'status-overdue',
                                default => 'status-pending'
                            };
                        @endphp
                        <tr>
                            <td>{{ $p->payment_date ? $p->payment_date->format('d/m/Y') : '—' }}</td>
                            <td>{{ $p->student->full_name ?? ($p->student->name ?? '—') }}</td>
                            <td>{{ $p->student->classe ?? '—' }}</td>
                            <td>{{ $p->fee->name ?? '—' }}</td>
                            <td class="amount">{{ number_format((float)$p->amount, 0, ',', ' ') }} Ar</td>
                            <td>{{ ucwords(str_replace('_',' ', $p->payment_method)) }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ ucfirst($p->status) }}</span></td>
                            <td>{{ $p->reference ?: '—' }}</td>
                            <td>{{ $p->receipt_number ?: '—' }}</td>
                            <td>
                                <a href="{{ route('payments.show', $p) }}" class="btn btn-warning">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; color:#666;">Aucune donnée trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($payments, 'links'))
            <div class="pagination" style="margin-top:1rem;">{{ $payments->links() }}</div>
        @endif
    </div>
</body>
</html>
