<?php
session_start();
require_once "../model/connect.php";

// Sprawdzenie, czy pole hasła zostało przesłane
if(isset($_POST["password"]) && !empty($_POST["password"])) {
    $pass = ", pass = '".password_hash($_POST["password"], PASSWORD_ARGON2ID)."'";
} else {
    $pass = ""; // Jeśli pole hasła nie zostało przesłane, ustawiamy puste pole w zapytaniu UPDATE
}

$sql = "UPDATE `users` SET 
        `role_id` = '$_POST[role_id]', 
        `firstName` = '$_POST[firstName]', 
        `lastName` = '$_POST[lastName]', 
        `birthday` = '$_POST[birthday]' 
        $pass
        WHERE `users`.`id` = $_SESSION[userIdUpdate];";

$conn->query($sql);

if ($conn->affected_rows == 0){
    $_SESSION["error"] = "Nie zaktualizowano użytkownika";
}else{
    $_SESSION["error"] = "Zaktualizowano użytkownika $_POST[firstName] $_POST[lastName]";
}

header("location: ../controller/usersmanagement.php");
