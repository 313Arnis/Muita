<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-CRM | Sākums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; min-height: 100vh; display: grid; place-items: center; }
        .hero-card { max-width: 800px; border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .btn-pill { border-radius: 50px; padding: 10px 25px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card hero-card mx-3">
        <div class="row g-0">
            <div class="col-md-5 bg-primary text-white d-flex flex-column justify-content-center p-5 text-center">
                <i class="fas fa-shield-halved fa-4x mb-3 text-white-50"></i>
                <h2 class="fw-bold">Muitas CRM</h2>
                <p class="small opacity-75">Efektīva robežkontroles un risku vadības sistēma</p>
            </div>
            <div class="col-md-7 p-4 p-lg-5 bg-white">
                <div class="row g-3 mb-4 text-center">
                    <div class="col-4">
                        <i class="fas fa-truck text-primary mb-2 d-block"></i>
                        <small class="fw-bold">Auto</small>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-magnifying-glass text-primary mb-2 d-block"></i>
                        <small class="fw-bold">Pārbaudes</small>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-chart-line text-primary mb-2 d-block"></i>
                        <small class="fw-bold">Dati</small>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-pill shadow-sm">
                        <i class="fas fa-chart-pie me-2"></i>Skatīt statistiku
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-pill flex-grow-1">Ienākt</a>
                        <a href="{{ route('register') }}" class="btn btn-light btn-pill border flex-grow-1">Reģistrēties</a>
                    </div>
                </div>

                <div class="p-2 bg-light rounded text-center">
                    <small class="text-muted" style="font-size: 0.75rem;">
                        <i class="fas fa-info-circle me-1"></i> Viesu piekļuve ierobežota
                    </small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>