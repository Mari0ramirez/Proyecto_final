<?php
session_start();
error_reporting(0); // Cambiar a error_reporting(E_ALL) para desarrollo
require_once('includes/config.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <link rel="icon" href="images/ues3.png" type="image/x-icon">
    <title>Sistema de Gestión de Resultados Estudiantiles | Panel Principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    
    <!-- Animate.css -->
    <link href="css/animate-css/animate.min.css" rel="stylesheet" />
    
    <!-- Toastr CSS -->
    <link href="css/toastr/toastr.min.css" rel="stylesheet" />
    
    <!-- Main CSS -->
    <link href="css/main.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="main-wrapper">

    <?php include('includes/topbar.php'); ?>
    <div class="content-wrapper">
        <div class="content-container d-flex">

            <?php include('includes/leftbar.php'); ?>  

            <main class="flex-grow-1 p-4">

                <div class="mb-4">
                    <h2>Panel Principal</h2>
                </div>

                <div class="row g-4">
                    <!-- Total de Estudiantes -->
                    <div class="col-md-6 col-lg-3">
                        <a href="manage-students.php" class="text-decoration-none" aria-label="Ir a gestión de estudiantes">
                            <div class="card text-bg-primary h-100 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <?php
                                    $query = $dbh->prepare("SELECT COUNT(*) FROM tblstudents");
                                    $query->execute();
                                    $totalstudents = $query->fetchColumn();
                                    ?>
                                    <h3 class="display-5 counter mb-2"><?= htmlentities($totalstudents); ?></h3>
                                    <p class="mb-0 fw-semibold">Usuarios Registrados</p>
                                    <i class="fa fa-users fa-2x mt-3" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Total de Asignaturas -->
                    <div class="col-md-6 col-lg-3">
                        <a href="manage-subjects.php" class="text-decoration-none" aria-label="Ir a gestión de asignaturas">
                            <div class="card text-bg-danger h-100 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <?php
                                    $query = $dbh->prepare("SELECT COUNT(*) FROM tblsubjects");
                                    $query->execute();
                                    $totalsubjects = $query->fetchColumn();
                                    ?>
                                    <h3 class="display-5 counter mb-2"><?= htmlentities($totalsubjects); ?></h3>
                                    <p class="mb-0 fw-semibold">Asignaturas Listadas</p>
                                    <i class="fa fa-ticket fa-2x mt-3" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Total de Clases -->
                    <div class="col-md-6 col-lg-3">
                        <a href="manage-classes.php" class="text-decoration-none" aria-label="Ir a gestión de clases">
                            <div class="card text-bg-warning h-100 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <?php
                                    $query = $dbh->prepare("SELECT COUNT(*) FROM tblclasses");
                                    $query->execute();
                                    $totalclasses = $query->fetchColumn();
                                    ?>
                                    <h3 class="display-5 counter mb-2"><?= htmlentities($totalclasses); ?></h3>
                                    <p class="mb-0 fw-semibold">Clases Listadas</p>
                                    <i class="fa fa-bank fa-2x mt-3" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Total de Resultados -->
                    <div class="col-md-6 col-lg-3">
                        <a href="manage-results.php" class="text-decoration-none" aria-label="Ir a gestión de resultados">
                            <div class="card text-bg-success h-100 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <?php
                                    $query = $dbh->prepare("SELECT COUNT(DISTINCT StudentId) FROM tblresult");
                                    $query->execute();
                                    $totalresults = $query->fetchColumn();
                                    ?>
                                    <h3 class="display-5 counter mb-2"><?= htmlentities($totalresults); ?></h3>
                                    <p class="mb-0 fw-semibold">Resultados Registrados</p>
                                    <i class="fa fa-file-text fa-2x mt-3" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>
<style>

 </style>  

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="js/jquery/jquery-2.2.4.min.js"></script>

<!-- Toastr JS -->
<script src="js/toastr/toastr.min.js"></script>

<!-- CounterUp -->
<script src="js/counterUp/jquery.counterup.min.js"></script>
<script src="js/waypoint/waypoints.min.js"></script>

<script>
$(function() {
    // Contador animado
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    // Mensaje de bienvenida
    toastr.options = {
        closeButton: true,
        progressBar: false,
        positionClass: "toast-top-right",
        timeOut: 4000
    };
    toastr.success("¡Bienvenido al Sistema de Gestión de Resultados Estudiantiles!");
});
</script>

</body>
</html>
