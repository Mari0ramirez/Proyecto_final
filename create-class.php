<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {   
    header("Location: index.php"); 
    exit();
}

$msg = "";
$error = "";
$classname = "";
$classnamenumeric = "";
$section = "";

if(isset($_POST['submit'])) {
    $classname = trim($_POST['classname']);
    $classnamenumeric = trim($_POST['classnamenumeric']); 
    $section = trim($_POST['section']);

    // Validaciones adicionales
    if(empty($classname) || empty($classnamenumeric) || empty($section)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Verificar si la clase ya existe
        $sql_check = "SELECT id FROM tblclasses WHERE ClassName = :classname AND ClassNameNumeric = :classnamenumeric AND Section = :section";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':classname', $classname, PDO::PARAM_STR);
        $query_check->bindParam(':classnamenumeric', $classnamenumeric, PDO::PARAM_STR);
        $query_check->bindParam(':section', $section, PDO::PARAM_STR);
        $query_check->execute();

        if($query_check->rowCount() > 0) {
            $error = "Esta clase ya está registrada.";
        } else {
            // Insertar nueva clase
            $sql = "INSERT INTO tblclasses (ClassName, ClassNameNumeric, Section) VALUES (:classname, :classnamenumeric, :section)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':classname', $classname, PDO::PARAM_STR);
            $query->bindParam(':classnamenumeric', $classnamenumeric, PDO::PARAM_STR);
            $query->bindParam(':section', $section, PDO::PARAM_STR);

            if($query->execute()) {
                $msg = "Clase creada exitosamente.";
                // Limpiar campos
                $classname = $classnamenumeric = $section = "";
            } else {
                $error = "Algo salió mal. Por favor, inténtelo de nuevo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Admin SMS Crear Clase</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>   
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>                   

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Crear Clase de Estudiantes</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li><a href="#">Clases</a></li>
                                    <li class="active">Crear Clase</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>Formulario para crear clase</h5>
                                            </div>
                                        </div>

                                        <?php if($msg): ?>
                                            <div class="succWrap"><strong>¡Bien hecho!</strong> <?=htmlspecialchars($msg)?></div>
                                        <?php elseif($error): ?>
                                            <div class="errorWrap"><strong>¡Vaya!</strong> <?=htmlspecialchars($error)?></div>
                                        <?php endif; ?>

                                        <div class="panel-body">
                                            <form method="post">
                                                <div class="form-group has-success">
                                                    <label>Nombre de la Clase</label>
                                                    <input type="text" name="classname" class="form-control" required value="<?=htmlspecialchars($classname)?>">
                                                    <span class="help-block">Ejemplo: Tercero, Cuarto, Sexto, etc.</span>
                                                </div>

                                                <div class="form-group has-success">
                                                    <label>Nombre de la Clase en Número</label>
                                                    <input type="number" name="classnamenumeric" class="form-control" required value="<?=htmlspecialchars($classnamenumeric)?>">
                                                    <span class="help-block">Ejemplo: 1, 2, 4, 5, etc.</span>
                                                </div>

                                                <div class="form-group has-success">
                                                    <label>Sección</label>
                                                    <input type="text" name="section" class="form-control" required value="<?=htmlspecialchars($section)?>">
                                                    <span class="help-block">Ejemplo: A, B, C, etc.</span>
                                                </div>

                                                <button type="submit" name="submit" class="btn btn-success btn-labeled">
                                                    Enviar<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
