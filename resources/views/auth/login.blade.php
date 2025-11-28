<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EduMaster</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .logo {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #20B2AA;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #20B2AA;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: #20B2AA;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #1a9a92;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: #20B2AA;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .demo-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .demo-info strong {
            color: #20B2AA;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">🎓 EduMaster</div>
        <div class="subtitle">Connexion au système</div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            @if ($errors->any())
                <div style="background: #fee; color: #c33; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required>
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem;">
                        👁️
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn">Se Connecter</button>
        </form>
        
        <div class="back-link">
            <a href="/">← Retour à l'accueil</a>
        </div>
        
        <div class="demo-info">
            <strong>Comptes de démonstration :</strong><br>
            • <strong>Admin :</strong> admin@edumaster.mg<br>
            • <strong>Directeur :</strong> director@edumaster.mg<br>
            • <strong>Professeur :</strong> teacher@edumaster.mg<br>
            • <strong>Mot de passe :</strong> password
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = passwordInput.nextElementSibling;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.innerHTML = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleButton.innerHTML = '👁️';
            }
        }
    </script>
</body>
</html>
