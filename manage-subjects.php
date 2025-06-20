<?php session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="") {
    header("Location: index.php"); 
} else {

// Código para eliminar materia
if(isset($_GET['id'])) { 
    $subid=$_GET['id'];
    $sql="DELETE FROM tblsubjects WHERE id = :subid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':subid', $subid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Materia eliminada.")</script>';
    echo "<script>window.location.href ='manage-subjects.php'</script>";
} 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Materias</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/prism/prism.css">
    <link rel="stylesheet" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <style>
        .errorWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap{
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">
    <?php include('includes/topbar.php');?> 
    <div class="content-wrapper">
        <div class="content-container">
            <?php include('includes/leftbar.php');?>  

            <div class="main-page">
                <div class="container-fluid">
                    <div class="row page-title-div">
                        <div class="col-md-6">
                            <h2 class="title">Administrar Materias</h2>
                        </div>
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                <li>Materias</li>
                                <li class="active">Administrar Materias</li>
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
                                            <h5>Lista de Materias</h5>
                                        </div>
                                    </div>
                                    <?php if($msg){?>
                                    <div class="alert alert-success left-icon-alert" role="alert">
                                        <strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?>
                                    </div>
                                    <?php } else if($error){?>
                                    <div class="alert alert-danger left-icon-alert" role="alert">
                                        <strong>¡Error!</strong> <?php echo htmlentities($error); ?>
                                    </div>
                                    <?php } ?>
                                    <div class="panel-body p-20">
                                        <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre de Materia</th>
                                                    <th>Código</th>
                                                    <th>Fecha Creación</th>
                                                    <th>Fecha Actualización</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre de Materia</th>
                                                    <th>Código</th>
                                                    <th>Fecha Creación</th>
                                                    <th>Fecha Actualización</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php 
                                                $sql = "SELECT * FROM tblsubjects";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt=1;
                                                if($query->rowCount() > 0) {
                                                    foreach($results as $result) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result->SubjectName);?></td>
                                                    <td><?php echo htmlentities($result->SubjectCode);?></td>
                                                    <td><?php echo htmlentities($result->Creationdate);?></td>
                                                    <td><?php echo htmlentities($result->UpdationDate);?></td>
                                                    <td>
                                                        <a href="edit-subject.php?subjectid=<?php echo htmlentities($result->id);?>" class="btn btn-info btn-xs">Editar</a> 
                                                        <a href="manage-subjects.php?id=<?php echo $result->id;?>&del=delete" onClick="return confirm('Confirma que deseas eliminar esta materia')" class="btn btn-danger btn-xs">Eliminar</a>
                                                    </td>
                                                </tr>
                                                <?php $cnt++; } } ?>
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

<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/prism/prism.js"></script>
<script src="js/DataTables/datatables.min.js"></script>
<script src="js/main.js"></script>
<script>
    $(function($) {
        $('#example').DataTable();
    });
</script>
</body>
</html>
<?php } ?>
