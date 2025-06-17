<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);        // Oculta errores al usuario final; déjalo en 1 en desarrollo

require_once 'includes/config.php';

/* ---------- 1. Comprobar sesión ---------- */
if (empty($_SESSION['alogin'])) {
    header("Location: index.php");
    exit;
}

/* ---------- 2. Validar ID de clase ---------- */
if (!isset($_GET['classid']) || !is_numeric($_GET['classid'])) {
    die("ID de clase inválido.");
}
$idClase = (int) $_GET['classid'];

/* ---------- 3. Generar token CSRF ---------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$mensaje = $error = "";

/* ---------- 4. Procesar actualización ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {

    /* 4.1 Comprobar token CSRF */
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = "Token CSRF inválido. Vuelve a cargar la página.";
    } else {
        /* 4.2 Recoger y sanitizar entradas */
        $nombreClase    = trim($_POST['nombreclase']);
        $nombreNumerico = trim($_POST['nombrenumerico']);
        $seccion        = trim($_POST['seccion']);

        if ($nombreClase === '' || $nombreNumerico === '' || $seccion === '') {
            $error = "Todos los campos son obligatorios.";
        } else {
            /* 4.3 Comprobar duplicados (excepto la propia clase) */
            $sqlDup = "SELECT id FROM tblclasses
                       WHERE ClassName = :nc
                         AND ClassNameNumeric = :nn
                         AND Section = :sec
                         AND id <> :idc";
            $dup = $dbh->prepare($sqlDup);
            $dup->execute([
                ':nc'  => $nombreClase,
                ':nn'  => $nombreNumerico,
                ':sec' => $seccion,
                ':idc' => $idClase
            ]);

            if ($dup->rowCount()) {
                $error = "Ya existe una clase con el mismo nombre, número y sección.";
            } else {
                /* 4.4 Actualizar */
                $sqlUpd = "UPDATE tblclasses
                           SET ClassName = :nc,
                               ClassNameNumeric = :nn,
                               Section = :sec
                           WHERE id = :idc";
                $upd = $dbh->prepare($sqlUpd);
                if ($upd->execute([
                        ':nc'  => $nombreClase,
                        ':nn'  => $nombreNumerico,
                        ':sec' => $seccion,
                        ':idc' => $idClase
                    ])) {
                    $mensaje      = "Los datos se actualizaron correctamente.";
                    // Renovar token para evitar doble envío
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $error = "Error al actualizar. Inténtalo de nuevo.";
                }
            }
        }
    }
}

/* ---------- 5. Obtener datos actuales ---------- */
$sql = "SELECT * FROM tblclasses WHERE id = :idc";
$stmt = $dbh->prepare($sql);
$stmt->execute([':idc' => $idClase]);
$datosClase = $stmt->fetch(PDO::FETCH_OBJ);
if (!$datosClase) {
    die("Clase no encontrada.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Actualizar Clase | SRMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & estilos -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .errorWrap{padding:10px;margin-bottom:20px;background:#fff;border-left:4px solid #dd3d36}
        .succWrap{padding:10px;margin-bottom:20px;background:#fff;border-left:4px solid #5cb85c}
    </style>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">

    <?php include 'includes/topbar.php'; ?>
    <div class="content-wrapper">
        <div class="content-container">
            <?php include 'includes/leftbar.php'; ?>

            <div class="main-page">
                <div class="container-fluid">
                    <div class="row page-title-div">
                        <div class="col-md-6"><h2 class="title">Actualizar Clase</h2></div>
                    </div>

                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                <li>Clases</li>
                                <li class="active">Actualizar Clase</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="panel">
                                    <div class="panel-heading"><h5>Formulario de actualización</h5></div>

                                    <?php if ($mensaje): ?>
                                        <div class="succWrap"><strong>Éxito:</strong> <?=htmlspecialchars($mensaje)?></div>
                                    <?php elseif ($error): ?>
                                        <div class="errorWrap"><strong>Error:</strong> <?=htmlspecialchars($error)?></div>
                                    <?php endif; ?>

                                    <div class="panel-body">
                                        <form method="post">
                                            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                                            
                                            <div class="form-group">
                                                <label>Nombre de la clase</label>
                                                <input type="text" name="nombreclase" class="form-control" required
                                                       value="<?=htmlspecialchars($_POST['nombreclase'] ?? $datosClase->ClassName)?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Nombre numérico</label>
                                                <input type="number" name="nombrenumerico" class="form-control" required
                                                       value="<?=htmlspecialchars($_POST['nombrenumerico'] ?? $datosClase->ClassNameNumeric)?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Sección</label>
                                                <input type="text" name="seccion" class="form-control" required
                                                       value="<?=htmlspecialchars($_POST['seccion'] ?? $datosClase->Section)?>">
                                            </div>

                                            <button type="submit" name="actualizar" class="btn btn-success">
                                                Actualizar <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div>
                        </div>
                    </div>
                </section>

            </div><!-- /.main-page -->
        </div><!-- /.content-container -->
    </div><!-- /.content-wrapper -->
</div><!-- /.main-wrapper -->

<!-- JS comunes -->
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
