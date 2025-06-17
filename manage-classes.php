<?php 
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}

$mensaje = "";
$error = "";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $classid = intval($_GET['id']);

    try {
        $sql = "DELETE FROM tblclasses WHERE id = :classid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classid', $classid, PDO::PARAM_INT);
        $query->execute();
        $mensaje = "Clase eliminada correctamente.";
    } catch (Exception $e) {
        $error = "Error al eliminar la clase: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin | Gestionar Clases</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Estilos -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="js/DataTables/datatables.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/modernizr/modernizr.min.js"></script>

    <style>
        .errorWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #dd3d36;
        }
        .succWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #5cb85c;
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
                            <h2 class="title">Gestionar Clases</h2>
                        </div>
                    </div>

                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                <li>Clases</li>
                                <li class="active">Gestionar Clases</li>
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
                                        <h5 class="panel-title">Ver Información de Clases</h5>
                                    </div>

                                    <?php if ($mensaje): ?>
                                        <div class="succWrap"><strong>Éxito:</strong> <?= htmlentities($mensaje); ?></div>
                                    <?php elseif ($error): ?>
                                        <div class="errorWrap"><strong>Error:</strong> <?= htmlentities($error); ?></div>
                                    <?php endif; ?>

                                    <div class="panel-body p-20">
                                        <table id="tablaClases" class="table table-striped table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre de Clase</th>
                                                    <th>Numérico</th>
                                                    <th>Sección</th>
                                                    <th>Fecha de Creación</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM tblclasses";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $resultados = $query->fetchAll(PDO::FETCH_OBJ);
                                                $contador = 1;
                                                foreach ($resultados as $fila): ?>
                                                    <tr>
                                                        <td><?= $contador++; ?></td>
                                                        <td><?= htmlentities($fila->ClassName); ?></td>
                                                        <td><?= htmlentities($fila->ClassNameNumeric); ?></td>
                                                        <td><?= htmlentities($fila->Section); ?></td>
                                                        <td><?= htmlentities($fila->CreationDate); ?></td>
                                                        <td>
                                                            <a href="edit-class.php?classid=<?= htmlentities($fila->id); ?>" class="btn btn-info btn-xs">Editar</a>
                                                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="<?= $fila->id; ?>">Eliminar</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
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

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelEliminar">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalLabelEliminar">Confirmar Eliminación</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        ¿Estás seguro que deseas eliminar esta clase?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <a href="#" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</a>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/DataTables/datatables.min.js"></script>
<script src="js/main.js"></script>

<script>
    $(document).ready(function () {
        $('#tablaClases').DataTable();

        $('#confirmarEliminarModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const idClase = button.data('id');
            const href = 'manage-classes.php?id=' + idClase;
            $('#btnConfirmarEliminar').attr('href', href);
        });
    });
</script>
</body>
</html>
