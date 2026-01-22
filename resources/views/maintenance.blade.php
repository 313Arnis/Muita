<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apkope - {{ \App\Models\Setting::get('system_name', 'MCRM') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; height: 100vh; display: grid; place-items: center; font-family: sans-serif; }
        .m-card { background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 400px; text-align: center; border-top: 5px solid #1a73e8; }
        .icon-box { font-size: 3rem; color: #1a73e8; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="m-card mx-3">
        <div class="icon-box"><i class="fas fa-screwdriver-wrench"></i></div>
        <h4 class="fw-bold text-dark">Sistēmas apkope</h4>
        <p class="text-muted small">Pašlaik veicam uzlabojumus. Lūdzu, mēģiniet vēlreiz pēc dažām minūtēm.</p>
        <hr>
        <div class="d-grid shadow-sm">
            <a href="/" class="btn btn-primary fw-bold py-2">Atjaunot lapu</a>
        </div>
        <p class="mt-3 mb-0 text-muted" style="font-size: 0.75rem;">Support: admin@customs.lv</p>
    </div>
</body>
</html>