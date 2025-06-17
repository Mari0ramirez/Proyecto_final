<?php
error_reporting(0);
include('includes/config.php'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <link rel="icon" href="images/ues3.png" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sistema de Gestión de Resultados</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Fonts (opcional) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .hero {
            background-image: url('https://images.unsplash.com/photo-1584697964154-5b0be97cf285?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
            height: 100vh;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
        }

        .content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 40px;
        }

        .btn-custom {
            padding: 15px 30px;
            font-size: 1.2rem;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        }

        .btn-primary { background-color: #0d6efd; }
        .btn-success { background-color: #198754; }
        .btn-dark    { background-color: #212529; }
    </style>
</head>
<body>

    <div class="hero">
        <div class="overlay"></div>
        <div class="d-flex justify-content-center align-items-center flex-column h-100 content">
            <h1> Sistema de Gestión de Resultados</h1>
            <div class="d-flex flex-wrap gap-4 justify-content-center">
                <a href="login_estudiante.php" class="btn btn-success btn-custom">Estudiantes</a>
                <a href="admin-login.php" class="btn btn-dark btn-custom">Administrador</a>
            </div>
        </div>
    </div>

</body>
</html>
