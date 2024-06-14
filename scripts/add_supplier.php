<?php
session_start();
foreach ($_POST as $key => $value){
    //echo "$key: $value<br>";
    if (empty($value)){
        //echo "$key<br>";
        //echo "error<br>";
        echo "<script>history.back();</script>";
        exit();
    }
}
require_once "../model/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION["logged"]["role_id"] == '2')
    {
        // Pobierz dane z formularza
        $company_name = $_POST["company_name"];

        // Dodaj firmę do bazy danych
        $sql = "INSERT INTO suppliers (`name`) VALUES ('$company_name')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION["error"] = "Nowa firma została dodana pomyślnie.";
        } else {
            $_SESSION["error"] = "Wystąpił błąd podczas dodawania firmy: " . $conn->error;
        }
    }
    else{
        $_SESSION["error"] = "Brak uprawnień";
    }
    // Przekieruj użytkownika z powrotem do odpowiedniej strony
    header("location: ../controller/suppliers.php");
    exit();
} else {
    // Jeśli skrypt nie został wywołany przez metodę POST, przekieruj użytkownika z powrotem do formularza
    header("location: ../controller/orders.php");
    exit();
}
?>
