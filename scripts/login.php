<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    session_start();

//Pętla sprawdzająca czy wszystkie pola formularza ($_POST) są wypełnione
    foreach ($_POST as $value){
        if (empty($value)){
            $_SESSION["error"] = "Wypełnij wszystkie dane!";
            echo "<script>history.back();</script>";
            exit();
        }
    }

    require_once "../model/connect.php";

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");

        $stmt->bind_param("s", $_POST["email"]);

        $stmt->execute();

        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        if ($result->num_rows != 0){
            //Pobranie adresu ip
            $address_ip = $_SERVER["REMOTE_ADDR"];
            //porównanie hasła
            if (password_verify($_POST["pass"], $user["pass"])){
                //przypisanie zmiennych sesyjnych po poprawnym zalogowaniu
                $_SESSION["logged"]["firstName"] = $user["firstName"];
                $_SESSION["logged"]["lastName"] = $user["lastName"];
                $_SESSION["logged"]["role_id"] = $user["role_id"];
                $_SESSION["logged"]["photo"] = $user["photo"];
                $_SESSION["logged"]["session_id"] = session_id();
                $_SESSION["logged"]["ID"] = $user["id"];
                $_SESSION["logged"]["last_activity"] = time();
                $_SESSION["logged"]["departament"] = $user["dep_id"];

                $status = 1;
                //exit();
                $sql = "INSERT INTO `logs` (`user_id`, `status`, `address_ip`) VALUES ( ?, ?, ?);";
                $stmt = $conn->prepare($sql);
// zabezpieczenie przed SQL injection - użycie spreparowanych instrukcji bind_param
                //https://www.php.net/manual/en/pdostatement.bindparam.php
                $stmt->bind_param("iss", $user["id"], $status,  $address_ip);
                $stmt->execute();

                //przeniesienie użytkownika do logged.php
                header("location: ../controller/orders.php");
                exit();
            }else{
                // obsługa błędów oraz wprowadzenie próby logowania do bazy danych
                $status = 0;
                //exit();
                $sql = "INSERT INTO `logs` (`user_id`, `status`, `address_ip`) VALUES ( ?, ?, ?);";
                $stmt = $conn->prepare($sql);

                $stmt->bind_param("iss", $user["id"], $status,  $address_ip);
                $stmt->execute();
                $_SESSION["error"] = "Błędny login lub hasło!";
                echo "<script>history.back();</script>";
                exit();
            }
        }else{

            $_SESSION["error"] = "Nie udało się zalogować!";
            echo "<script>history.back();</script>";
            exit();
        }

    } catch(mysqli_sql_exception $e) {
        $_SESSION["error"] = $e->getMessage();
        echo "<script>history.back();</script>";
        exit();
    }
}
$conn->close();
header("location: ../register.php");