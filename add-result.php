<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Verificar si el usuario está logueado
if (empty($_SESSION['alogin'])) {
    header("Location: index.php");
    exit;
}

$msg = "";
$error = "";

if (isset($_POST['submit'])) {
    $classId = $_POST['class'];
    $studentId = $_POST['studentid'];
    $marks = $_POST['marks'];

    if (empty($classId) || empty($studentId) || empty($marks)) {
        $error = "Por favor, complete todos los campos requeridos.";
    } else {
        // Obtener los IDs de las materias asociadas a la clase seleccionada
        $stmt = $dbh->prepare("SELECT tblsubjects.SubjectName, tblsubjects.id 
                               FROM tblsubjectcombination 
                               JOIN tblsubjects ON tblsubjects.id = tblsubjectcombination.SubjectId 
                               WHERE tblsubjectcombination.ClassId = :classId 
                               ORDER BY tblsubjects.SubjectName");
        $stmt->bindValue(':classId', $classId, PDO::PARAM_INT);
        $stmt->execute();

        $subjectIds = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $subjectIds[] = $row['id'];
        }

        // Validar que la cantidad de notas y materias coincidan
        if (count($marks) != count($subjectIds)) {
            $error = "El número de calificaciones no coincide con el número de materias.";
        } else {
            $allInserted = true;

            // Insertar las notas en la tabla tblresult
            for ($i = 0; $i < count($marks); $i++) {
                $markValue = $marks[$i];
                $subjectId = $subjectIds[$i];

                // Puedes agregar validación extra para la nota (ej. que sea número y rango válido)

                $sql = "INSERT INTO tblresult(StudentId, ClassId, SubjectId, marks) 
                        VALUES (:studentId, :classId, :subjectId, :marks)";
                $query = $dbh->prepare($sql);
                $query->bindValue(':studentId', $studentId, PDO::PARAM_INT);
                $query->bindValue(':classId', $classId, PDO::PARAM_INT);
                $query->bindValue(':subjectId', $subjectId, PDO::PARAM_INT);
                $query->bindValue(':marks', $markValue, PDO::PARAM_STR);

                if (!$query->execute()) {
                    $allInserted = false;
                    break; // Salir si alguna inserción falla
                }
            }

            if ($allInserted) {
                $msg = "¡Resultado registrado con éxito!";
            } else {
                $error = "Ocurrió un error al registrar los resultados. Por favor, inténtelo de nuevo.";
            }
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
    <title>SMS Admin | Agregar Resultados</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
    <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
    <link rel="stylesheet" href="css/select2/select2.min.css" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/select2/select2.min.js"></script>

    <script>
    // Función para obtener estudiantes y materias según clase seleccionada
    function getStudent(classId) {
        $.ajax({
            type: "POST",
            url: "get_student.php",
            data: { classid: classId },
            success: function(data) {
                $("#studentid").html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: "get_student.php",
            data: { classid1: classId },
            success: function(data) {
                $("#subject").html(data);
            }
        });
    }

    // Función para obtener resultados (opcional, puedes mejorarla o integrarla según tus necesidades)
    function getresult(studentId) {
        var classId = $(".clid").val();
        var val = $(".stid").val();
        var param = classId + '$' + val;

        $.ajax({
            type: "POST",
            url: "get_student.php",
            data: { studclass: param },
            success: function(data) {
                $("#reslt").html(data);
            }
        });
    }

    $(document).ready(function() {
        $(".js-states").select2();
        $(".js-states-limit").select2({
            maximumSelectionLength: 2
        });
        $(".js-states-hide").select2({
            minimumResultsForSearch: Infinity
        });
    });
    </script>
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
                                <h2 class="title">Declarar Resultados</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li class="active">Resultado del estudiante</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <?php if ($msg) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>¡Bien hecho!</strong> <?php echo htmlspecialchars($msg); ?>
                                            </div>
                                        <?php } else if ($error) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>¡Ups!</strong> <?php echo htmlspecialchars($error); ?>
                                            </div>
                                        <?php } ?>

                                        <form class="form-horizontal" method="post" autocomplete="off">

                                            <div class="form-group">
                                                <label for="classid" class="col-sm-2 control-label">Clase</label>
                                                <div class="col-sm-10">
                                                    <select name="class" class="form-control clid" id="classid" onChange="getStudent(this.value);" required>
                                                        <option value="">Seleccione una clase</option>
                                                        <?php
                                                        $sql = "SELECT * FROM tblclasses";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) {
                                                                echo '<option value="' . htmlentities($result->id) . '">' . 
                                                                     htmlentities($result->ClassName) . ' - Sección ' . htmlentities($result->Section) . 
                                                                     '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="studentid" class="col-sm-2 control-label">Nombre del estudiante</label>
                                                <div class="col-sm-10">
                                                    <select name="studentid" class="form-control stid" id="studentid" required onChange="getresult(this.value);">
                                                        <!-- Opciones cargadas por AJAX -->
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-10 col-sm-offset-2">
                                                    <div id="reslt"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="subject" class="col-sm-2 control-label">Asignaturas</label>
                                                <div class="col-sm-10">
                                                    <div id="subject"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" id="submit" class="btn btn-primary">Declarar Resultados</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- /.main-page -->

            </div> <!-- /.content-container -->
        </div> <!-- /.content-wrapper -->
    </div> <!-- /.main-wrapper -->
</body>
</html>
