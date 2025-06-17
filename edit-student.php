<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}

$stid = intval($_GET['stid']);

if (isset($_POST['submit'])) {
    // Corrijo nombre del input 'fullanme' a 'fullname' para evitar confusión
    $studentname = trim($_POST['fullname']);
    $roolid = trim($_POST['rollid']);
    $studentemail = trim($_POST['emailid']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $status = $_POST['status'];

    // Validación básica (puedes agregar más validaciones según necesidad)
    if (empty($studentname) || empty($roolid) || empty($studentemail) || empty($gender) || empty($dob) || !isset($status)) {
        $error = "Por favor, complete todos los campos obligatorios.";
    } else if (!filter_var($studentemail, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingrese un correo electrónico válido.";
    } else {
        $sql = "UPDATE tblstudents 
                SET StudentName = :studentname, RollId = :roolid, StudentEmail = :studentemail, 
                    Gender = :gender, DOB = :dob, Status = :status 
                WHERE StudentId = :stid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentname', $studentname, PDO::PARAM_STR);
        $query->bindParam(':roolid', $roolid, PDO::PARAM_STR);
        $query->bindParam(':studentemail', $studentemail, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':stid', $stid, PDO::PARAM_INT);
        if ($query->execute()) {
            $msg = "Información del estudiante actualizada correctamente.";
        } else {
            $error = "Error al actualizar la información. Intente de nuevo.";
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
    <title>Administración SMS | Editar Estudiante</title>
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

        <!-- Contenido principal -->
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
                                    <li class="active">Editar Estudiante</li>
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
                                            <h5>Complete la información del estudiante</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">

                                    <?php if (isset($msg)) { ?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?>
                                        </div>
                                    <?php } elseif (isset($error)) { ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>¡Error!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                    <?php } ?>

                                    <form class="form-horizontal" method="post" autocomplete="off">
                                    <?php 
                                    $sql = "SELECT s.StudentName, s.RollId, s.RegDate, s.StudentId, s.Status, s.StudentEmail, s.Gender, s.DOB, c.ClassName, c.Section
                                            FROM tblstudents s
                                            JOIN tblclasses c ON c.id = s.ClassId
                                            WHERE s.StudentId = :stid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':stid', $stid, PDO::PARAM_INT);
                                    $query->execute();
                                    $result = $query->fetch(PDO::FETCH_OBJ);

                                    if ($result) {  
                                    ?>

                                        <div class="form-group">
                                            <label for="fullname" class="col-sm-2 control-label">Nombre completo</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="fullname" id="fullname" class="form-control" 
                                                    value="<?php echo htmlentities($result->StudentName); ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="rollid" class="col-sm-2 control-label">ID de matrícula</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="rollid" id="rollid" class="form-control" maxlength="5" 
                                                    value="<?php echo htmlentities($result->RollId); ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="emailid" class="col-sm-2 control-label">Correo electrónico</label>
                                            <div class="col-sm-10">
                                                <input type="email" name="emailid" id="emailid" class="form-control" 
                                                    value="<?php echo htmlentities($result->StudentEmail); ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Género</label>
                                            <div class="col-sm-10">
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Male" required
                                                    <?php echo ($result->Gender == "Male") ? 'checked' : ''; ?>> Masculino
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Female" required
                                                    <?php echo ($result->Gender == "Female") ? 'checked' : ''; ?>> Femenino
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Other" required
                                                    <?php echo ($result->Gender == "Other") ? 'checked' : ''; ?>> Otro
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="classname" class="col-sm-2 control-label">Clase</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="classname" class="form-control" 
                                                    value="<?php echo htmlentities($result->ClassName . " (" . $result->Section . ")"); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="dob" class="col-sm-2 control-label">Fecha de nacimiento</label>
                                            <div class="col-sm-10">
                                                <input type="date" name="dob" id="dob" class="form-control" 
                                                    value="<?php echo htmlentities($result->DOB); ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Fecha de registro</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static"><?php echo htmlentities($result->RegDate); ?></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Estado</label>
                                            <div class="col-sm-10">
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" value="1" required
                                                    <?php echo ($result->Status == "1") ? 'checked' : ''; ?>> Activo
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" value="0" required
                                                    <?php echo ($result->Status == "0") ? 'checked' : ''; ?>> Bloqueado
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" name="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </div>

                                    <?php } else { ?>
                                        <p>No se encontró información para este estudiante.</p>
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
