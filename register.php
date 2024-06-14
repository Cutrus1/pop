<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stołówka K32 | Rejestracja</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">

  <?php
  // w przypadku błędu wyłapanego w register_user.php pojawi się div z komunikatem
    if (isset($_SESSION["error"])){
      echo <<< ERROR
        <div class="callout callout-danger">
          <h5>Błąd!</h5>
          <p>$_SESSION[error]</p>
        </div>
ERROR;
      unset($_SESSION["error"]);
    }
  ?>

  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./index.php" class="h1"><b>Stołówka</b>K32</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Zarejestruj się</p>

      <form action="./scripts/register_user.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Podaj imię" name="firstName" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Podaj nazwisko" name="lastName" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Podaj adres email" name="email1" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Powtórz adres email" name="email2" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Podaj hasło" name="pass1" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Powtórz hasło" name="pass2" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="date" class="form-control" name="birthday" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-calendar"></span>
            </div>
          </div>
        </div>

          <div class="input-group mb-3">
              <input type="photo" class="form-control" placeholder="wklej link do zdjęcia" name="photo" required>
              <div class="input-group-append">
                  <div class="input-group-text">
                      <span class="fas fa-photo-video"></span>
                  </div>
              </div>
          </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               Akceptuję <a href="#">regulamin</a>
              </label>
            </div>
          </div>
            <br>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Zarejestruj</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <a href="index.php" class="text-center">Mam już konto</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
</body>
</html>
