<?php
require_once "../model/connect.php";

session_start();

//wylogowanie użytkownika po wygaśnięciu sesji (status !=2)
if (!isset($_SESSION["logged"]) || session_status() != 2) {
    $_SESSION["error"] = "Zaloguj się!";
    header("location: ../");
} else {
    //przypisanie do zmiennej roli użytkownika
    switch ($_SESSION["logged"]["role_id"]) {
        case 1:
            $_SESSION["role"] = "Użytkownik";
            break;
        case 2:
            $_SESSION["role"] = "Moderator";
            break;
        case 3:
            $_SESSION["role"] = "Administrator";
            break;
        case 4:
            $_SESSION["role"] = "Księgowy";
            break;
    }
}

if (isset($_SESSION["logged"]["last_activity"])) {
    $lastActivityTime = $_SESSION["logged"]["last_activity"];
    $currentTime = time();
    $sessionTimeout = 900; //15 minut (15 * 60s)

    if ($currentTime - $lastActivityTime > $sessionTimeout) {
        $_SESSION["error"] = "Sesja użytkownika wygasła!";
        unset($_SESSION["logged"]);
        header("location: ../");
        exit();
    }
} else {
    $_SESSION["error"] = "Sesja użytkwonika wygasła lub nie jest aktywna!";
    header("location: ../");
}



    function create_suppliers(){
        if (!isset($_GET['addsupplier']))
        {
            // Dodanie formularza do dodawania nowej firmy
            echo <<<NEW_COMPANY_FORM
            <div class="card-header">
                <h3 class="card-title">Dodaj nową firmę</h3>
            </div>
            <div class="card-body">
                <form action="../scripts/add_supplier.php" method="post">
                    <label for="company_name">Nazwa firmy:</label><br>
                    <input type="text" id="company_name" name="company_name"><br><br>       
                    <input type="submit" value="Dodaj firmę">
                </form>
            </div>
    NEW_COMPANY_FORM;
        }
        // ...
    }
        



// You can also perform other logic in the controller

// Load the view
include "../views/ViewSuppliers.php"; // Adjust the path accordingly

