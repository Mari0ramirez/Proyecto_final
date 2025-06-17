<?php
session_start();
// Activar reporte de errores para desarrollo, desactiva en producción
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

// Validar sesión de administrador
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit();
}

// Manejo de mensajes flash (éxito/error)
$msg = '';
$error = '';
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrador - Gestionar Estudiantes</title>

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
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">

    <!-- Barra superior -->
    <?php include('includes/topbar.php');?>

    <!-- Contenedor principal -->
    <div class="content-wrapper">
        <div class="content-container">

            <!-- Barra lateral -->
            <?php include('includes/leftbar.php');?>

            <div class="main-page">
                <div class="container-fluid">

                    <div class="row page-title-div">
                        <div class="col-md-6">
                            <h2 class="title">Gestionar Estudiantes</h2>
                        </div>
                    </div>

                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                <li>Estudiantes</li>
                                <li class="active">Gestionar Estudiantes</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="container-fluid">

                        <?php if($msg){ ?>
                        <div class="alert alert-success left-icon-alert" role="alert">
                            <strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?>
                        </div>
                        <?php } else if($error){ ?>
                        <div class="alert alert-danger left-icon-alert" role="alert">
                            <strong>¡Error!</strong> <?php echo htmlentities($error); ?>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">

                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5>Lista de Estudiantes</h5>
                                        </div>
                                    </div>

                                    <div class="panel-body p-20">

                                        <table id="tablaEstudiantes" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre del Estudiante</th>
                                                    <th>ID de Matrícula</th>
                                                    <th>Clase</th>
                                                    <th>Fecha de Registro</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre del Estudiante</th>
                                                    <th>ID de Matrícula</th>
                                                    <th>Clase</th>
                                                    <th>Fecha de Registro</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php 
                                                $sql = "SELECT tblstudents.StudentName, tblstudents.RollId, tblstudents.RegDate, tblstudents.StudentId, tblstudents.Status, tblclasses.ClassName, tblclasses.Section 
                                                        FROM tblstudents 
                                                        JOIN tblclasses ON tblclasses.id = tblstudents.ClassId 
                                                        ORDER BY tblstudents.RegDate DESC";

                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                $contador = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) { ?>
                                                        <tr>
                                                            <td><?php echo $contador; ?></td>
                                                            <td><?php echo htmlentities($result->StudentName); ?></td>
                                                            <td><?php echo htmlentities($result->RollId); ?></td>
                                                            <td><?php echo htmlentities($result->ClassName); ?> (<?php echo htmlentities($result->Section); ?>)</td>
                                                            <td><?php echo htmlentities(date("d-m-Y", strtotime($result->RegDate))); ?></td>
                                                            <td>
                                                                <?php 
                                                                if ($result->Status == 1) {
                                                                    echo '<span class="text-success">Activo</span>';
                                                                } else {
                                                                    echo '<span class="text-danger">Bloqueado</span>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <a href="edit-student.php?stid=<?php echo urlencode($result->StudentId); ?>" class="btn btn-primary btn-xs" target="_blank" title="Editar estudiante">
                                                                    <i class="fa fa-edit"></i> Editar
                                                                </a> 
                                                                <a href="edit-result.php?stid=<?php echo urlencode($result->StudentId); ?>" class="btn btn-warning btn-xs" target="_blank" title="Ver resultados">
                                                                    <i class="fa fa-file-text"></i> Resultados
                                                                </a>
                                                                <a href="delete-student.php?stid=<?php echo urlencode($result->StudentId); ?>" 
                                                                   class="btn btn-danger btn-xs" 
                                                                   onclick="return confirm('¿Está seguro que desea eliminar este estudiante? Esta acción no se puede deshacer.');" 
                                                                   title="Eliminar estudiante">
                                                                    <i class="fa fa-trash"></i> Eliminar
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php 
                                                        $contador++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div> <!-- panel-body -->

                                </div> <!-- panel -->
                            </div> <!-- col-md-12 -->
                        </div> <!-- row -->

                    </div> <!-- container-fluid -->
                </section>

            </div> <!-- main-page -->

        </div> <!-- content-container -->
    </div> <!-- content-wrapper -->

</div> <!-- main-wrapper -->

<!-- JS comunes -->
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>

<!-- JS DataTables -->
<script src="js/prism/prism.js"></script>
<script src="js/DataTables/datatables.min.js"></script>

<script src="js/main.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaEstudiantes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            },
            "order": [[4, "desc"]] // Ordenar por fecha de registro descendente
        });
    });
</script>

</body>
</html>
