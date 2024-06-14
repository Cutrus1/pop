<?php
session_start();


require_once "../model/connect.php";

if ($_SESSION["logged"]["role_id"]=='2')
{
    $sql = "UPDATE `orders` SET `account_id` = '$_POST[account_id]', status_id = '$_POST[status_id]' WHERE `orders`.`id` = $_SESSION[orderIdUpdate];";
    $conn->query($sql);

    if ($conn->affected_rows == 0){
        $_SESSION["error"] = "Nie zaktualizowano zamówienia - brak zmian";
    }else{
        $_SESSION["error"] = "Zaktualizowano zamówienie o ID: $_SESSION[orderIdUpdate]";
    }
}
else
{
    $_SESSION["error"] = "Brak uprawnień - musisz być moderatorem";
}




header("location: ../controller/orders.php");