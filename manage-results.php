<?php
session_start();
error_reporting(0); // En producción está bien, pero en desarrollo usa error_reporting(E_ALL);
include('includes/config.php');

// Verificar que el usuario está logueado
if(strlen($_SESSION['alogin']) == 0) {   
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Resultados de Estudiantes</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
    <link rel="stylesheet" href="css/prism/prism.css" media="screen" > <!-- Se puede eliminar si no es necesario -->
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen" >

    <script src="js/modernizr/modernizr.min.js"></script>

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

        <!-- Barra superior -->
        <?php include('includes/topbar.php'); ?> 

        <div class="content-wrapper">
            <div class="content-container">

                <!-- Barra lateral izquierda -->
                <?php include('includes/leftbar.php'); ?>  

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Administrar Resultados</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li>Resultados</li>
                                    <li class="active">Administrar Resultados</li>
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
                                                <h5>Ver Información de Resultados de Estudiantes</h5>
                                            </div>
                                        </div>

                                        <?php if(!empty($msg)) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>¡Éxito!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } else if(!empty($error)) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>¡Error!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>

                                        <div class="panel-body p-20">
                                            <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre del Estudiante</th>
                                                        <th>ID de Rol</th>
                                                        <th>Clase</th>
                                                        <th>Fecha de Registro</th>
                                                        <th>Estado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre del Estudiante</th>
                                                        <th>ID de Rol</th>
                                                        <th>Clase</th>
                                                        <th>Fecha de Registro</th>
                                                        <th>Estado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php 
                                                    $sql = "SELECT DISTINCT 
                                                                tblstudents.StudentName,
                                                                tblstudents.RollId,
                                                                tblstudents.RegDate,
                                                                tblstudents.StudentId,
                                                                tblstudents.Status,
                                                                tblclasses.ClassName,
                                                                tblclasses.Section 
                                                            FROM tblresult 
                                                            JOIN tblstudents ON tblstudents.StudentId = tblresult.StudentId  
                                                            JOIN tblclasses ON tblclasses.id = tblresult.ClassId";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;

                                                    if($query->rowCount() > 0) {
                                                        foreach($results as $result) { ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($result->StudentName); ?></td>
                                                                <td><?php echo htmlentities($result->RollId); ?></td>
                                                                <td><?php echo htmlentities($result->ClassName); ?> (<?php echo htmlentities($result->Section); ?>)</td>
                                                                <td><?php echo htmlentities($result->RegDate); ?></td>
                                                                <td>
                                                                    <?php 
                                                                    echo ($result->Status == 1) ? 'Activo' : 'Bloqueado'; 
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <a href="edit-result.php?stid=<?php echo htmlentities($result->StudentId); ?>" class="btn btn-primary btn-xs">Editar</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        $cnt++;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="7" style="text-align:center;">No se encontraron resultados.</td>
                                                        </tr>
                                                    <?php } ?>
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

    <!-- Archivos JS comunes -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>

    <!-- Archivos JS para DataTables y Prism -->
    <script src="js/prism/prism.js"></script>
    <script src="js/DataTables/datatables.min.js"></script>

    <!-- JS principal -->
    <script src="js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
            });
        });
    </script>
</body>
</html>
