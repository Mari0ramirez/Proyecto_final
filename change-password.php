<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php"); 
} else {
    if(isset($_POST['submit'])) {
        $password = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $username = $_SESSION['alogin'];

        $sql = "SELECT Password FROM admin WHERE UserName = :username AND Password = :password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount() > 0) {
            $update = "UPDATE admin SET Password = :newpassword WHERE UserName = :username";
            $updateQuery = $dbh->prepare($update);
            $updateQuery->bindParam(':username', $username, PDO::PARAM_STR);
            $updateQuery->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $updateQuery->execute();
            $msg = "Tu contraseña ha sido cambiada exitosamente.";
        } else {
            $error = "Tu contraseña actual es incorrecta.";    
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Cambiar Contraseña (Admin)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script>
    function validarFormulario() {
        if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
            alert("¡La nueva contraseña y la confirmación no coinciden!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>

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
        <?php include('includes/topbar.php');?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php');?>

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Cambiar Contraseña (Admin)</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
                                    <li class="active">Cambiar contraseña</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <h5>Cambiar contraseña de administrador</h5>
                                        </div>

                                        <?php if(isset($msg)){ ?>
                                            <div class="succWrap"><strong>¡Bien hecho!</strong> <?php echo htmlentities($msg); ?></div>
                                        <?php } else if(isset($error)) { ?>
                                            <div class="errorWrap"><strong>¡Error!</strong> <?php echo htmlentities($error); ?></div>
                                        <?php } ?>

                                        <div class="panel-body">
                                            <form name="chngpwd" method="post" onsubmit="return validarFormulario();">
                                                <div class="form-group">
                                                    <label>Contraseña actual</label>
                                                    <input type="password" name="password" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nueva contraseña</label>
                                                    <input type="password" name="newpassword" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Confirmar nueva contraseña</label>
                                                    <input type="password" name="confirmpassword" class="form-control" required>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-success">Cambiar <i class="fa fa-check"></i></button>
                                            </form>
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

    <!-- Scripts -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
