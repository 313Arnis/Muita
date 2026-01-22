<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muitas CRM - Ieiet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header-blue { background: #1a73e8; color: white; border-radius: 12px 12px 0 0; padding: 30px; text-align: center; }
        .btn-primary { background: #1a73e8; border: none; padding: 12px; border-radius: 8px; font-weight: 600; }
    </style>
</head>
<body>

<div class="login-card card">
    <div class="header-blue">
        <i class="fas fa-shield-halved fa-3x mb-3"></i>
        <h3>{{ \App\Models\Setting::get('system_name', 'Muitas CRM') }}</h3>
    </div>
    
    <div class="card-body p-4">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger py-2 small">Nepareizi piekļuves dati.</div>
            @endif

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Lietotājvārds</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control bg-light border-start-0" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Parole</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-start-0" required>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label small" for="remember">Atcerēties mani</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                IEIET SISTĒMĀ
            </button>
        </form>

        <div class="mt-4 p-2 bg-light rounded text-center small border">
            <span class="text-muted">Testa piekļuve:</span> <strong>admin / admin</strong>
        </div>
    </div>
</div>

</body>
</html>