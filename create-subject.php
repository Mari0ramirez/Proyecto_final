<?php
session_start();
include('includes/config.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}

$mensajeExito = "";
$mensajeError = "";

// Procesar formulario al enviar
if (isset($_POST['submit'])) {
    $nombreAsignatura = trim($_POST['subjectname']);
    $codigoAsignatura = trim($_POST['subjectcode']);

    if (!empty($nombreAsignatura) && !empty($codigoAsignatura)) {
        $sql = "INSERT INTO tblsubjects (SubjectName, SubjectCode) VALUES (:subjectname, :subjectcode)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectname', $nombreAsignatura, PDO::PARAM_STR);
        $query->bindParam(':subjectcode', $codigoAsignatura, PDO::PARAM_STR);

        if ($query->execute()) {
            $mensajeExito = "Asignatura creada correctamente.";
        } else {
            $mensajeError = "Hubo un error al crear la asignatura. Intente nuevamente.";
        }
    } else {
        $mensajeError = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Crear Asignatura | Panel de Administración</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
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
                                <h2 class="title">Crear Asignatura</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li>Asignaturas</li>
                                    <li class="active">Crear Asignatura</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h5>Formulario para crear nueva asignatura</h5>
                                    </div>
                                    <div class="panel-body">

                                        <?php if (!empty($mensajeExito)) : ?>
                                            <div class="alert alert-success" role="alert">
                                                <strong>¡Éxito!</strong> <?php echo htmlentities($mensajeExito); ?>
                                            </div>
                                        <?php elseif (!empty($mensajeError)) : ?>
                                            <div class="alert alert-danger" role="alert">
                                                <strong>¡Error!</strong> <?php echo htmlentities($mensajeError); ?>
                                            </div>
                                        <?php endif; ?>

                                        <form class="form-horizontal" method="post">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nombre de la Asignatura</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="subjectname" class="form-control" placeholder="Ingrese el nombre" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Código de la Asignatura</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="subjectcode" class="form-control" placeholder="Ingrese el código" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Crear Asignatura</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div> <!-- /.row -->
                    </div> <!-- /.container-fluid -->

                </div> <!-- /.main-page -->
            </div> <!-- /.content-container -->
        </div> <!-- /.content-wrapper -->
    </div> <!-- /.main-wrapper -->

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
