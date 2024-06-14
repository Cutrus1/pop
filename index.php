<?php
session_start();
if (isset($_SESSION["logged"]) && session_status() == 2){
    header("location: ./controller/orders.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Company System | Logowanie</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <?php
    if (isset($_SESSION["error"])){
        echo <<< ERROR
        <div class="callout callout-danger">
          <h5>Błąd!</h5>
          <p>$_SESSION[error]</p>
        </div>
ERROR;
        unset($_SESSION["error"]);
    }

    if (isset($_SESSION["success"])){
        echo <<< ERROR
        <div class="callout callout-success">
          <h5>Gratulacje!</h5>
          <p>$_SESSION[success]</p>
        </div>
ERROR;
        unset($_SESSION["success"]);
    }
    ?>
    <div class="card card-outline card-yellow">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Company System</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Logowanie użytkownika</p>

            <form action="./scripts/login.php" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Podaj email" name="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Podaj hasło" name="pass">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-5">
                    <button type="submit" class="btn btn-primary btn-block" style="background-color: grey">Logowanie</button>
                </div>
                <!-- /.col -->
            </form>
            <p class="mb-0">
                <a href="./register.php" class="text-center">Zarejestruj nowe konto</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
</body>
</html>
