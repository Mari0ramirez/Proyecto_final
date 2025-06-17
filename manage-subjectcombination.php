<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Verificar si el usuario está logueado
if (strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}

// Activar asignatura
if (isset($_GET['activate_id'])) {
    $activate_id = intval($_GET['activate_id']);
    $status = 1;
    $sql = "UPDATE tblsubjectcombination SET status = :status WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->bindParam(':id', $activate_id, PDO::PARAM_INT);
    $query->execute();
    $msg = "Asignatura activada correctamente";
}

// Desactivar asignatura
if (isset($_GET['deactivate_id'])) {
    $deactivate_id = intval($_GET['deactivate_id']);
    $status = 0;
    $sql = "UPDATE tblsubjectcombination SET status = :status WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->bindParam(':id', $deactivate_id, PDO::PARAM_INT);
    $query->execute();
    $msg = "Asignatura desactivada correctamente";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Gestionar Combinaciones de Asignaturas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
    <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <script src="js/modernizr/modernizr.min.js"></script>
    <style>
        .errorWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- Barra de navegación superior -->
        <?php include('includes/topbar.php');?>

        <div class="content-wrapper">
            <div class="content-container">

                <!-- Barra lateral -->
                <?php include('includes/leftbar.php');?>

                <div class="main-page">
                    <div class="container-fluid">

                        <!-- Título y breadcrumbs -->
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Gestionar Combinaciones de Asignaturas</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li>Asignaturas</li>
                                    <li class="active">Gestionar Combinaciones de Asignaturas</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <section class="section">
                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>Ver información de combinaciones de asignaturas</h5>
                                            </div>
                                        </div>

                                        <!-- Mensajes de éxito o error -->
                                        <?php if(isset($msg)){ ?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?>
                                        </div>
                                        <?php } ?>

                                        <div class="panel-body p-20">

                                            <table id="subjectTable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Clase y Sección</th>
                                                        <th>Asignatura</th>
                                                        <th>Estado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Clase y Sección</th>
                                                        <th>Asignatura</th>
                                                        <th>Estado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php 
                                                    $sql = "SELECT c.ClassName, c.Section, s.SubjectName, sc.id AS scid, sc.status 
                                                            FROM tblsubjectcombination sc
                                                            JOIN tblclasses c ON c.id = sc.ClassId
                                                            JOIN tblsubjects s ON s.id = sc.SubjectId";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $count = 1;

                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                            $statusText = ($row->status == 1) ? 'Activo' : 'Inactivo';
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($count); ?></td>
                                                        <td><?php echo htmlentities($row->ClassName); ?> - Sección <?php echo htmlentities($row->Section); ?></td>
                                                        <td><?php echo htmlentities($row->SubjectName); ?></td>
                                                        <td><?php echo $statusText; ?></td>
                                                        <td>
                                                            <?php if ($row->status == 0) { ?>
                                                                <a href="manage-subjectcombination.php?activate_id=<?php echo htmlentities($row->scid); ?>" 
                                                                   onclick="return confirm('¿Realmente desea activar esta asignatura?');" 
                                                                   title="Activar">
                                                                   <i class="fa fa-check"></i>
                                                                </a>
                                                            <?php } else { ?>
                                                                <a href="manage-subjectcombination.php?deactivate_id=<?php echo htmlentities($row->scid); ?>" 
                                                                   onclick="return confirm('¿Realmente desea desactivar esta asignatura?');" 
                                                                   title="Desactivar">
                                                                   <i class="fa fa-times"></i>
                                                                </a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                            $count++;
                                                        }
                                                    } 
                                                    ?>
                                                </tbody>
                                            </table>

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

    <!-- Scripts JS -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>
    <script src="js/prism/prism.js"></script>
    <script src="js/DataTables/datatables.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('#subjectTable').DataTable();
        });
    </script>
</body>
</html>
