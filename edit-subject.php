<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Verificar que el usuario esté logueado
if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php");
    exit();
} else {
    if(isset($_POST['Actualizar'])) {
        $idAsignatura = intval($_GET['subjectid']);
        $nombreAsignatura = $_POST['subjectname'];
        $codigoAsignatura = $_POST['subjectcode'];

        $sql = "UPDATE tblsubjects SET SubjectName=:nombre, SubjectCode=:codigo WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':nombre', $nombreAsignatura, PDO::PARAM_STR);
        $query->bindParam(':codigo', $codigoAsignatura, PDO::PARAM_STR);
        $query->bindParam(':id', $idAsignatura, PDO::PARAM_INT);
        $query->execute();

        $msg = "Información de la asignatura actualizada correctamente";
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrador SMS - Actualizar Asignatura</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
    <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
    <link rel="stylesheet" href="css/select2/select2.min.css" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <script src="js/modernizr/modernizr.min.js"></script>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- Barra superior -->
        <?php include('includes/topbar.php');?> 

        <div class="content-wrapper">
            <div class="content-container">

                <!-- Barra lateral izquierda -->
                <?php include('includes/leftbar.php');?>  

                <div class="main-page">

                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Actualizar Asignatura</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li>Asignaturas</li>
                                    <li class="active">Actualizar Asignatura</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5>Actualizar Asignatura</h5>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        <?php if(isset($msg)) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } elseif(isset($error)) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>¡Error!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>

                                        <form class="form-horizontal" method="post">
                                            <?php
                                            $idAsignatura = intval($_GET['subjectid']);
                                            $sql = "SELECT * FROM tblsubjects WHERE id=:id";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':id', $idAsignatura, PDO::PARAM_INT);
                                            $query->execute();
                                            $resultado = $query->fetch(PDO::FETCH_OBJ);

                                            if($resultado) { ?>
                                                <div class="form-group">
                                                    <label for="nombreAsignatura" class="col-sm-2 control-label">Nombre de la asignatura</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="subjectname" value="<?php echo htmlentities($resultado->SubjectName); ?>" class="form-control" id="nombreAsignatura" placeholder="Nombre de la asignatura" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="codigoAsignatura" class="col-sm-2 control-label">Código de la asignatura</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="subjectcode" value="<?php echo htmlentities($resultado->SubjectCode); ?>" class="form-control" id="codigoAsignatura" placeholder="Código de la asignatura" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" name="Actualizar" class="btn btn-primary">Actualizar</button>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <p>No se encontró la asignatura solicitada.</p>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>
    <script src="js/prism/prism.js"></script>
    <script src="js/select2/select2.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        $(function() {
            $(".js-states").select2();
            $(".js-states-limit").select2({
                maximumSelectionLength: 2
            });
            $(".js-states-hide").select2({
                minimumResultsForSearch: Infinity
            });
        });
    </script>

</body>
</html>
<?php } ?>
