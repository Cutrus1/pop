<?php
session_start();

//wylogowanie użytkownika po wygaśnięciu sesji (status !=2)

if (!isset($_SESSION["logged"]) || session_status() != 2){
    $_SESSION["error"] = "Zaloguj się!";
    header("location: ../");
}

if (isset($_SESSION["logged"]["last_activity"])){
    //echo $_SESSION["logged"]["last_activity"];
    $lastActivityTime = $_SESSION["logged"]["last_activity"];
    $currentTime = time();
    $sessionTimeout = 900;

    if ($currentTime - $lastActivityTime > $sessionTimeout){
        $_SESSION["error"] = "Sesja użytkownika wygasła!";
        unset($_SESSION["logged"]);
        header("location: ../");
        exit();
    }
}else{
    $_SESSION["error"] = "Sesja użytkwonika wygasła lub nie jest aktywna!";
    header("location: ../");
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Purchase Orders</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <script src="https://kit.fontawesome.com/2dd0ce50c8.js" crossorigin="anonymous"></script>
        <!-- DataTables -->
        <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    </head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    
    <?php
    include "../views/navModerator.php";
    include "../views/aside.php";
    ?>
    <div class="content-wrapper">
    <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Użytkownicy</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <?php
                                require_once "../model/connect.php";

                                //aktualizacja użytkownika
                                if (isset($_GET["userIdUpdate"])){
                                $_SESSION["userIdUpdate"] = $_GET["userIdUpdate"];
                                $sql = "SELECT * FROM users WHERE users.id = '$_GET[userIdUpdate]'";
                                $result = $conn->query($sql);
                                $updateUser = $result->fetch_assoc();
                                echo <<< UPDATEUSERFORM
                                <h4>Aktualizacja użytkownika</h4>
                                <form action="../scripts/update_user.php" method="post">
                                <label for="firstName">Imie: </label>
                                    <input type="text" name="firstName" placeholder="Podaj imię" autofocus value="$updateUser[firstName]"><br><br>
                                <label for="lastName">Nazwisko: </label>
                                    <input type="text" name="lastName" placeholder="Podaj nazwisko" value="$updateUser[lastName]"><br><br>
                                    <input type="date" name="birthday" value="$updateUser[birthday]">Data urodzenia<br><br>
                                    

                                    <input type="checkbox" id="changePassword" name="changePassword">
                                    <label for="changePassword">Zmień hasło</label><br><br>
                                    <div id="passwordField" style="display: none;">
                                    <label for="password">Nowe hasło:</label>
                                    <input type="password" name="password" placeholder="Wprowadź nowe hasło">


                                    </div>
                                <br>
                               <label for="role_id">rola: </label>
                                    <select name="role_id">
                              UPDATEUSERFORM;
                                        $sql = "SELECT id, role FROM roles";
                                        $result = $conn->query($sql);
                                        while($role = $result->fetch_assoc()){
                                        echo "<option value=$role[id]>$role[role]</option>";
                                        }
                                        echo <<< UPDATEUSERFORM
                                    </select>
                                    <br>
                                    <br>
                                    <input type="submit" value="Aktualizuj użytkownika">
                                </form>
                                <br>
                               UPDATEUSERFORM;
                                }
                                ?>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Imię</th>
                                        <th>Nazwisko</th>
                                        <th>email</th>
                                        <th>Data urodzenia</th>
                                        <th>Rola</th>
                                        <th>akcja</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT u.id userID,u.email, u.firstName,u.lastName,r.role,u.birthday FROM users u JOIN roles r ON r.id=u.role_id GROUP BY userID";
                                    $result = $conn->query($sql);
                                    //echo $result->num_rows;

                                    if ($result->num_rows == 0){
                                        echo "<tr><td colspan='6'>Brak rekordów do wyświetlenia</td></tr>";
                                    }else{
                                        while($user = $result->fetch_assoc()){
                                            echo <<< TABLEUSERS
				<tr>
					<td>$user[firstName]</td>
					<td>$user[lastName]</td>
					<td>$user[email]</td>
                    <td>$user[birthday]</td>
					<td>$user[role]</td>
                    <!--<td><a href="../scripts/delete_user.php?userIdDelete=$user[userID]">Usuń</a></td>-->
					<td><a href="./usersmanagement.php?userIdUpdate=$user[userID]">Aktualizuj</a></td>
				</tr>
TABLEUSERS;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Imię</th>
                                        <th>Nazwisko</th>
                                        <th>email</th>
                                        <th>Data urodzenia</th>
                                        <th>Rola</th>
                                        <th>akcja</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </div>
    <?php
    include "../views/footer.php";
    ?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
</body>
</html>

<!--skrypt, który, po kliknięcia checkboxa pokazuje diva ze zmainą hg -->
<script>
    // Funkcja obsługująca zmianę widoczności pola hasła w zależności od stanu checkboxa
    document.getElementById('changePassword').addEventListener('change', function() {
        var passwordField = document.getElementById('passwordField');
        if (this.checked) {
            passwordField.style.display = 'block';
        } else {
            passwordField.style.display = 'none';
        }
    });
</script>