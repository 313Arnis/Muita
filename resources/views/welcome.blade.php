<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muitas CRM - Laipni lūdzam</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
        }

        .welcome-header {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .welcome-body {
            padding: 40px 30px;
            text-align: center;
        }

        .btn-customs {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.2s ease;
            margin: 0 5px;
        }

        .btn-customs:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #0d47a1 0%, #1a73e8 100%);
        }

        .btn-outline-customs {
            border: 2px solid #1a73e8;
            color: #1a73e8;
            background: transparent;
            padding: 10px 28px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin: 0 5px;
        }

        .btn-outline-customs:hover {
            background: #1a73e8;
            color: white;
            transform: translateY(-2px);
        }

        .feature-icon {
            font-size: 2rem;
            color: #1a73e8;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="welcome-card">
                    <div class="welcome-header">
                        <i class="fas fa-customs fa-4x mb-3"></i>
                        <h1>Muitas Pārbaudes Punktu CRM</h1>
                        <p class="mb-0">Mūsdienīga muitas pārbaudes punktu vadības sistēma</p>
                    </div>
                    <div class="welcome-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="feature-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <h5>Transporta līdzekļi</h5>
                                <p>Kontrolējiet un pārvaldiet visus transporta līdzekļus sistēmā</p>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h5>Pārbaudes</h5>
                                <p>Veiciet detalizētas muitas pārbaudes un riska novērtējumus</p>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h5>Statistika</h5>
                                <p>Sekojiet līdzi sistēmas darbības rādītājiem</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-customs text-white">
                                <i class="fas fa-eye"></i> Skatīt statistiku
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-customs">
                                <i class="fas fa-sign-in-alt"></i> Pierakstīties
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-customs">
                                <i class="fas fa-user-plus"></i> Reģistrēties
                            </a>
                        </div>

                        <div class="alert alert-light" role="alert">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Viesi var apskatīt vispārējo statistiku. Pilnai piekļuvei nepieciešams lietotāja konts.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>