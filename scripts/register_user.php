<?php
session_start();

//Pętla sprawdzająca czy wszystkie pola formularza ($_POST) są wypełnione
foreach ($_POST as $value){
	if (empty($value)){
		$_SESSION["error"] = "Wypełnij wszystkie dane!";
		echo "<script>history.back();</script>";
		exit();
	}
}
//obsługa błędów - przypisanie do zmiennej sesyjnej erroe
$error = 0;
if (!isset($_POST["terms"])){
	$error = 1;
	$_SESSION["error"] = "Zatwierdź regulamin!";
}
//wymagania dot. hasła Duża, mała litera,cyfra, znak specjalny, 8 znaków
if(!preg_match('/(?!=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s])\S{8,}/', $_POST["pass1"])){
    $error = 1;
    $_SESSION["error"] = "Hasło nie spełnia wymagań";
}
if ($_POST["pass1"] != $_POST["pass2"]){
	$error = 1;
	$_SESSION["error"] = "Hasła są różne!";
}

if ($_POST["email1"] != $_POST["email2"]){
	$error = 1;
	$_SESSION["error"] = "Adresy poczty elektronicznej są różne!";
}
//Przerwanie wykonania skrytpu gdy wystąpi błąd
if ($error != 0){
	echo "<script>history.back();</script>";
	exit();
}

require_once "../model/connect.php";
// wprowadzanie danych z formularza do bazy
try {
	$stmt = $conn->prepare("INSERT INTO `users` (`email`, `firstName`, `lastName` , `birthday`, `pass`, `photo`, `created_at`) VALUES (?,?, ?, ?, ?,?, current_timestamp());");
// szyfrowanie hasła za pomocą metody ARGON2ID
	$pass = password_hash($_POST["pass1"], PASSWORD_ARGON2ID);
// zabezpieczenie przed SQL injection - użycie spreparowanych instrukcji bind_param
    //https://www.php.net/manual/en/pdostatement.bindparam.php
	$stmt->bind_param("ssssss",  $_POST["email1"], $_POST["firstName"], $_POST["lastName"], $_POST["birthday"], $pass, $_POST["photo"]);
	$stmt->execute();

    //obsługa błędów oraz pomyślnego dodania
	if ($stmt->affected_rows == 1){
		$_SESSION["success"] = "Prawidłowo dodano użytkownika $_POST[firstName] $_POST[lastName]";
	}else{
		$_SESSION["error"] = "Nie udało się dodać użytkownika!";
	}
} catch(mysqli_sql_exception $e) {
		$_SESSION["error"] = $e->getMessage();
		echo "<script>history.back();</script>";
		exit();
}

header("location: ../");