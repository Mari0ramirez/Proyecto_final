<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Verificar si el usuario ha iniciado sesión
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
} else {
    if(isset($_POST['submit'])) {
        // Recoger datos del formulario
        $studentname = $_POST['fullanme'];
        $roolid = $_POST['rollid']; 
        $studentemail = $_POST['emailid']; 
        $gender = $_POST['gender']; 
        $classid = $_POST['class']; 
        $dob = $_POST['dob']; 
        $status = 1;

        // Preparar consulta SQL para insertar nuevo estudiante
        $sql = "INSERT INTO tblstudents(StudentName, RollId, StudentEmail, Gender, ClassId, DOB, Status) 
                VALUES(:studentname, :roolid, :studentemail, :gender, :classid, :dob, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentname', $studentname, PDO::PARAM_STR);
        $query->bindParam(':roolid', $roolid, PDO::PARAM_STR);
        $query->bindParam(':studentemail', $studentemail, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);

        // Ejecutar consulta
        $query->execute();

        // Verificar si la inserción fue exitosa
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $msg = "Información del estudiante agregada exitosamente";
        } else {
            $error = "Algo salió mal. Por favor, inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Admisión de Estudiante</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
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
                                <h2 class="title">Admisión de Estudiante</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li class="active">Admisión de Estudiante</li>
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
                                            <h5>Llena la información del estudiante</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <?php if($msg){ ?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } else if($error){ ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>¡Error!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>

                                        <form class="form-horizontal" method="post">

                                            <div class="form-group">
                                                <label for="fullanme" class="col-sm-2 control-label">Nombre Completo</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="fullanme" class="form-control" id="fullanme" required autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="rollid" class="col-sm-2 control-label">ID de Matrícula</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="rollid" class="form-control" id="rollid" maxlength="5" required autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email" class="col-sm-2 control-label">Correo Electrónico</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="emailid" class="form-control" id="email" required autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Género</label>
                                                <div class="col-sm-10">
                                                    <input type="radio" name="gender" value="Male" required checked> Masculino
                                                    <input type="radio" name="gender" value="Female" required> Femenino
                                                    <input type="radio" name="gender" value="Other" required> Otro
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="class" class="col-sm-2 control-label">Clase</label>
                                                <div class="col-sm-10">
                                                    <select name="class" class="form-control" id="class" required>
                                                        <option value="">Seleccione Clase</option>
                                                        <?php 
                                                        $sql = "SELECT * from tblclasses";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if($query->rowCount() > 0) {
                                                            foreach($results as $result) { ?>
                                                                <option value="<?php echo htmlentities($result->id); ?>">
                                                                    <?php echo htmlentities($result->ClassName); ?> - Sección <?php echo htmlentities($result->Section); ?>
                                                                </option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="dob" class="col-sm-2 control-label">Fecha de Nacimiento</label>
                                                <div class="col-sm-10">
                                                    <input type="date" name="dob" class="form-control" id="dob">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Agregar</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->

        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/select2/select2.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(function($) {
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
?>
