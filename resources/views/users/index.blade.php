<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - EduMaster</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 2rem;
            color: #20B2AA;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            background: #20B2AA;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #1a9a92;
        }
        
        .users-table {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .actions form {
            margin: 0;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            text-decoration: none;
            border-radius: 4px;
            color: white;
        }
        
        .btn-edit {
            background: #ffc107;
        }
        
        .btn-delete {
            background: #dc3545;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
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
            <h1 class="page-title">👥 Gestion des Utilisateurs</h1>
            <a href="{{ route('users.create') }}" class="btn">+ Nouvel Utilisateur</a>
        </div>
        
        @if (session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="users-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="{{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('users.edit', $user) }}" class="btn-sm btn-edit btn-icon" title="Modifier l'utilisateur" aria-label="Modifier l'utilisateur">✏️</a>
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Supprimer {{ $user->name }} ?\n\nCette action est irréversible !')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete btn-icon" style="border: none; cursor: pointer;" title="Supprimer l'utilisateur" aria-label="Supprimer l'utilisateur">🗑️</button>
                                    </form>
                                @else
                                    <span class="btn-sm" style="background: #ccc; color: #666;">Votre compte</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
