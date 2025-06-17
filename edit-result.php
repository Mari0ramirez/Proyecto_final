<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
    exit();
} else {
    $stid = intval($_GET['stid']);
    $msg = "";
    $error = "";

    if (isset($_POST['submit'])) {
        $rowid = $_POST['id'];
        $marks = $_POST['marks'];

        if (empty($rowid) || empty($marks) || count($rowid) !== count($marks)) {
            $error = "Error: Los datos enviados no son válidos.";
        } else {
            try {
                $dbh->beginTransaction();

                $sql = "UPDATE tblresult SET marks = :mrks WHERE id = :iid";
                $query = $dbh->prepare($sql);

                foreach ($rowid as $count => $id) {
                    $mrks = trim($marks[$count]);
                    if (!is_numeric($mrks) || $mrks < 0 || $mrks > 100) {
                        throw new Exception("La nota ingresada para el registro #" . ($count+1) . " no es válida. Debe estar entre 0 y 100.");
                    }
                    $iid = intval($id);

                    $query->bindParam(':mrks', $mrks, PDO::PARAM_STR);
                    $query->bindParam(':iid', $iid, PDO::PARAM_INT);
                    $query->execute();
                }

                $dbh->commit();
                $msg = "¡Información de resultados actualizada exitosamente!";

            } catch (Exception $e) {
                $dbh->rollBack();
                $error = "Error al actualizar las notas: " . $e->getMessage();
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
    <title>SMS Admin | Información de resultados de estudiante</title>
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

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?> 
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">

                <!-- ========== LEFT SIDEBAR ========== -->
                <?php include('includes/leftbar.php');?>  
                <!-- /.left-sidebar -->

                <div class="main-page">

                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Información de resultados de estudiante</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li class="active">Información de resultados</li>
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
                                            <h5>Actualizar información de resultados</h5>
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

                                        <form class="form-horizontal" method="post" id="updateForm" onsubmit="return validarFormulario();">

                                            <?php 
                                            $ret = "SELECT tblstudents.StudentName, tblclasses.ClassName, tblclasses.Section 
                                                    FROM tblresult 
                                                    JOIN tblstudents ON tblresult.StudentId = tblstudents.StudentId 
                                                    JOIN tblsubjects ON tblsubjects.id = tblresult.SubjectId 
                                                    JOIN tblclasses ON tblclasses.id = tblstudents.ClassId 
                                                    WHERE tblstudents.StudentId = :stid 
                                                    LIMIT 1";
                                            $stmt = $dbh->prepare($ret);
                                            $stmt->bindParam(':stid',$stid,PDO::PARAM_INT);
                                            $stmt->execute();
                                            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                                            if ($stmt->rowCount() > 0) {
                                                foreach ($result as $row) { ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Clase</label>
                                                        <div class="col-sm-10">
                                                            <?php echo htmlentities($row->ClassName) ?> (<?php echo htmlentities($row->Section) ?>)
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Nombre completo</label>
                                                        <div class="col-sm-10">
                                                            <?php echo htmlentities($row->StudentName); ?>
                                                        </div>
                                                    </div>
                                            <?php } 
                                            } 
                                            ?>

                                            <?php 
                                            $sql = "SELECT DISTINCT tblstudents.StudentName, tblstudents.StudentId, tblclasses.ClassName, tblclasses.Section, tblsubjects.SubjectName, tblresult.marks, tblresult.id AS resultid 
                                                    FROM tblresult 
                                                    JOIN tblstudents ON tblstudents.StudentId = tblresult.StudentId 
                                                    JOIN tblsubjects ON tblsubjects.id = tblresult.SubjectId 
                                                    JOIN tblclasses ON tblclasses.id = tblstudents.ClassId 
                                                    WHERE tblstudents.StudentId = :stid";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':stid', $stid, PDO::PARAM_INT);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label"><?php echo htmlentities($result->SubjectName) ?></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id[]" value="<?php echo htmlentities($result->resultid) ?>">
                                                            <input type="text" name="marks[]" class="form-control marks-input" value="<?php echo htmlentities($result->marks) ?>" maxlength="5" required autocomplete="off" pattern="\d+(\.\d{1,2})?" title="Ingrese un número válido (ejemplo: 85 o 85.5)">
                                                        </div>
                                                    </div>
                                            <?php }
                                            } 
                                            ?>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Actualizar</button>
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
            // Validación en JS para que solo se acepten notas entre 0 y 100
            function validarFormulario() {
                const inputs = document.querySelectorAll('.marks-input');
                for(let i = 0; i < inputs.length; i++) {
                    const valor = inputs[i].value.trim();
                    if (valor === '' || isNaN(valor) || valor < 0 || valor > 100) {
                        alert('Por favor, ingrese una nota válida entre 0 y 100 en el campo "' + inputs[i].closest('.form-group').querySelector('label').innerText + '".');
                        inputs[i].focus();
                        return false;
                    }
                }
                return true;
            }

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
<?php } ?>
