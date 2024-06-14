<?php
session_start();

require_once "../model/connect.php";

$sql = "SELECT id,status_id FROM `orders` WHERE `orders`.`id` = $_SESSION[orderIdApprove];";
$result = $conn->query($sql);
$CURRENT_STATUS = $result->fetch_assoc();
if ($CURRENT_STATUS["status_id"] == 1) {

//sprawdzanie czy zalogowany użytkownik jest administratorem
    if ($_SESSION["logged"]["role_id"] == '3' or $_SESSION["logged"]["role_id"] == '2') {
        //zmiana starusu zamówienia na 2 (zaakceptowane)
        $sql = "UPDATE `orders` SET status_id = '2' WHERE `orders`.`id` = $_SESSION[orderIdApprove];";
        $conn->query($sql);
        if ($conn->affected_rows == 0) {
            $_SESSION["error"] = "Błąd - nie zatwierdzono zamówienia";
        } else {
            $_SESSION["error"] = "Zatwierdzono zamówienie o ID: $_SESSION[orderIdApprove]";
        }
    } else {
        $_SESSION["error"] = "Brak uprawnień do zatwierdzania zamówień";
    }
}
else{
    $_SESSION["error"] = "Zamówienie $_SESSION[orderIdApprove] nie jest w statusie Requsted - Nie można go zaakceptować";
}

header("location: ../controller/orders.php");