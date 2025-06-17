<?php
session_start();
error_reporting(0);
include('includes/config.php');

if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

if (isset($_POST['login'])) {
    $usuario = $_POST['username'];
    $contrasena = md5($_POST['password']);

    $sql = "SELECT UserName, Password FROM admin WHERE UserName = :usuario AND Password = :contrasena";
    $query = $dbh->prepare($sql);
    $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $query->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        echo "<script>alert('Datos inválidos');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
        <link rel="icon" href="images/ues3.png" type="image/x-icon">

    <title>Inicio de Sesión - Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font (opcional) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .login-container {
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #198754;
        }

        .btn-success {
            width: 100%;
            border-radius: 10px;
        }

        .logo-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #198754;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container login-container d-flex justify-content-center align-items-center">
    <div class="col-md-6 col-lg-4">
        <div class="card p-4">
            <div class="text-center logo-title">Sistema de Gestión de Resultados</div>
            <h5 class="text-center mb-4">Inicio de Sesión - Administrador</h5>
            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Ingrese su usuario" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" name="login" class="btn btn-success">Iniciar sesión</button>
            </form>
            <div class="footer-text text-center">© 2025 Sistema de Resultados Académicos</div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS (opcional si no usas componentes interactivos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
