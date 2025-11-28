<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Paiements - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 2rem; color: #20B2AA; }
        .btn { padding: 0.75rem 1.5rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.3s; }
        .btn:hover { background: #1a9a92; }
        .stats-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #20B2AA; }
        .stat-label { color: #666; margin-top: 0.5rem; }
        .payments-table { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; color: #333; }
        .status-paid { background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
        .status-pending { background: #fff3cd; color: #856404; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
        .status-overdue { background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
        .amount { font-weight: bold; color: #20B2AA; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; text-decoration: none; border-radius: 4px; color: white; }
        .btn-view { background: #17a2b8; }
        .btn-edit { background: #ffc107; }
        .btn-delete { background: #dc3545; }
        .action-buttons { display:flex; gap:0.5rem; justify-content:center; align-items:center; }
        .action-buttons form { margin:0; }
        .btn-icon { width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
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
            <h1 class="page-title">💰 Gestion des Paiements</h1>
            <a href="{{ route('payments.create') }}" class="btn">+ Nouveau Paiement</a>
        </div>

        @if(session('success'))
            <div style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('payments.index') }}" class="stats-cards" style="gap:1rem;">
            <div class="stat-card" style="text-align:left;">
                <label style="display:block;color:#555;font-weight:600;margin-bottom:.5rem;">Élève</label>
                <select name="student_id" style="width:100%;padding:.5rem;border:2px solid #e1e5e9;border-radius:8px;">
                    <option value="">Tous</option>
                    @foreach(($students ?? []) as $st)
                        <option value="{{ $st->id }}" {{ (string)($filters['student_id'] ?? '') === (string)$st->id ? 'selected' : '' }}>{{ $st->full_name ?? $st->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="stat-card" style="text-align:left;">
                <label style="display:block;color:#555;font-weight:600;margin-bottom:.5rem;">Statut</label>
                <select name="status" style="width:100%;padding:.5rem;border:2px solid #e1e5e9;border-radius:8px;">
                    <option value="">Tous</option>
                    @foreach(($statuses ?? []) as $stt)
                        <option value="{{ $stt }}" {{ ($filters['status'] ?? '')===$stt ? 'selected' : '' }}>{{ ucfirst($stt) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="stat-card" style="text-align:left;">
                <label style="display:block;color:#555;font-weight:600;margin-bottom:.5rem;">Méthode</label>
                <select name="payment_method" style="width:100%;padding:.5rem;border:2px solid #e1e5e9;border-radius:8px;">
                    <option value="">Toutes</option>
                    @foreach(($methods ?? []) as $m)
                        <option value="{{ $m }}" {{ ($filters['payment_method'] ?? '')===$m ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ', $m)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="stat-card" style="text-align:left;">
                <label style="display:block;color:#555;font-weight:600;margin-bottom:.5rem;">Du</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" style="width:100%;padding:.5rem;border:2px solid #e1e5e9;border-radius:8px;" />
                <label style="display:block;color:#555;font-weight:600;margin:.75rem 0 .5rem;">Au</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" style="width:100%;padding:.5rem;border:2px solid #e1e5e9;border-radius:8px;" />
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <button type="submit" class="btn">Filtrer</button>
                <a href="{{ route('payments.index') }}" class="btn" style="background:#6c757d;">Réinitialiser</a>
            </div>
        </form>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number">{{ number_format((float)($stats['month_total'] ?? 0), 0, ',', ' ') }}</div>
                <div class="stat-label">Ar - Recettes du mois (période filtrée)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['received_count'] ?? 0 }}</div>
                <div class="stat-label">Paiements reçus</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['pending_count'] ?? 0 }}</div>
                <div class="stat-label">Paiements en attente</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['overdue_count'] ?? 0 }}</div>
                <div class="stat-label">Paiements en retard</div>
            </div>
        </div>
        
        <div class="payments-table">
            <table>
                <thead>
                    <tr>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Type de frais</th>
                        <th>Montant</th>
                        <th>Date d'échéance</th>
                        <th>Date de paiement</th>
                        <th>Statut</th>
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
                            <td>{{ $p->student->full_name ?? ($p->student->name ?? '—') }}</td>
                            <td>{{ $p->classe ?? ($p->student->classe ?? '—') }}</td>
                            <td>{{ $p->fee->name ?? '—' }}</td>
                            <td class="amount">{{ number_format((float)$p->amount, 0, ',', ' ') }} Ar</td>
                            <td>{{ $p->fee?->due_date ? $p->fee->due_date->format('d/m/Y') : '—' }}</td>
                            <td>{{ $p->payment_date ? $p->payment_date->format('d/m/Y') : '—' }}</td>
                            <td><span class="{{ $statusClass }}">{{ ucfirst($p->status) }}</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('payments.show', $p) }}" class="btn-sm btn-view btn-icon" title="Voir le paiement" aria-label="Voir le paiement">👁️</a>
                                    <a href="{{ route('payments.edit', $p) }}" class="btn-sm btn-edit btn-icon" title="Modifier le paiement" aria-label="Modifier le paiement">✏️</a>
                                    <form action="{{ route('payments.destroy', $p) }}" method="POST" onsubmit="return confirm('Supprimer ce paiement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete btn-icon" title="Supprimer le paiement" aria-label="Supprimer le paiement">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#666;">Aucun paiement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($payments))
            <div style="margin-top: 1rem;">{{ $payments->links() }}</div>
        @endif
    </div>
</body>
</html>
