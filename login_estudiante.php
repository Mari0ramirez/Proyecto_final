<?php
session_start();
include('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="images/ues3.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Resultados Escolares</title>

    <!-- Bootstrap y estilos -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-panel {
            background: #ffffff;
            border-radius: 12px;
            padding: 35px 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.6s ease-in-out;
        }

        .login-panel h3 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }

        .form-control {
            border-radius: 6px;
        }

        .btn-success {
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #333;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 576px) {
            .login-panel {
                margin: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="login-panel">
        <h3>Consulta de Resultados</h3>
        <form action="result.php" method="post">
            <div class="form-group">
                <label for="rollid">ID de Estudiante</label>
                <input type="text" class="form-control" id="rollid" name="rollid" placeholder="Ingrese su ID" required>
            </div>

            <div class="form-group">
                <label for="default">Clase</label>
                <select name="class" class="form-control" id="default" required>
                    <option value="">Seleccione una clase</option>
                    <?php 
                    $sql = "SELECT * from tblclasses";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) { ?>
                            <option value="<?php echo htmlentities($result->id); ?>">
                                <?php echo htmlentities($result->ClassName); ?> - Sección <?php echo htmlentities($result->Section); ?>
                            </option>
                    <?php }} ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-search"></i> Buscar
            </button>

            <a href="index.php" class="back-link">Volver al inicio</a>
        </form>
    </div>

    <!-- Scripts -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
