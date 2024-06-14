<?php
session_start();


require_once "../model/connect.php";

//sprawdzanie czy zalogowany użytkownik jest księgowym lub moderatorem
if ($_SESSION["logged"]["role_id"] == '4' OR $_SESSION["logged"]["role_id"] == '2')
{
    //zmiana starusu zamówienia na 2 (zaakceptowane)
    $sql = "UPDATE `orders` SET status_id = '5' WHERE `orders`.`id` = $_SESSION[orderIdInvoice];";
    $conn->query($sql);
    if ($conn->affected_rows == 0){
        $_SESSION["error"] = "Błąd - nie zafakturowano zamówienia";
    }else{
        $_SESSION["error"] = "Zafakturowano zamówienie o ID: $_SESSION[orderIdInvoice]";
    }
}
else
{
    $_SESSION["error"] = "Brak uprawnień do fakturowania zamówień";
}


header("location: ../controller/orders.php");