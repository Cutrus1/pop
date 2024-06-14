<?php
session_start();
//zaczytanie wszystkich danych z formularza
foreach ($_POST as $key => $value){
    if (empty($value)){
        echo "<script>history.back();</script>";
        exit();
    }
}
require_once "../model/connect.php";
$sql = "SELECT created_by, u.email email FROM `orders` o JOIN users u ON u.id=o.created_by WHERE o.id = $_SESSION[orderIdStock];";
$result = $conn->query($sql);
$order = $result->fetch_assoc();
//$user = $_SESSION["logged"]["ID"];
//sprawdzanie czy osoba zalogowana tworzyła to zamówienie lub czy jest ona moderatorem
if ($_SESSION["logged"]["ID"] == $order['created_by'] OR $_SESSION["logged"]["role_id"]=='2')
{
    //zmiana ilości na podstawie formularza
    $sql = "UPDATE `ordered_items` SET `delivered_quantity` = '$_POST[item]' WHERE `ordered_items`.`id` = $_GET[ID]";;
    $conn->query($sql);
    if ($conn->affected_rows == 0){
        $_SESSION["error"] = "Błąd - nie przyjęto pozycji";
    }else{
        $_SESSION["error"] = "Zaktualizowano stan na: $_POST[item] szt. z pozycji $_GET[ID] z zamówienia o ID: $_SESSION[orderIdStock]";
    }

//zmiana statusu z zaakceptowanego na dostarczone lub częściowo dostarczone
    $sql = "SELECT oi.id ID, sum(quantity) sum_quantity, sum(delivered_quantity) sum_delivered FROM `ordered_items` oi JOIN items i ON oi.item_id = i.id WHERE oi.order_id=$_SESSION[orderIdStock];";
    $result = $conn->query($sql);
    $order_stock = $result->fetch_assoc();

    if ($order_stock['sum_delivered'] > 0 AND $order_stock['sum_delivered'] !== $order_stock['sum_quantity']){
        //zmiana statusu zamówienia na 3 (częściowo dostarczone)
        $sql = "UPDATE `orders` SET status_id = '3' WHERE `orders`.`id` = $_SESSION[orderIdStock];";
        $conn->query($sql);
    }
    else if ($order_stock['sum_delivered'] == $order_stock['sum_quantity'])
    {
        //zmiana statusu zamówienia na 4 (dostarczone)
        $sql = "UPDATE `orders` SET status_id = '4' WHERE `orders`.`id` = $_SESSION[orderIdStock];";
        $conn->query($sql);
        $_SESSION["error"] = "Przyjęto całe zamówienie";
    }
    else{
        $_SESSION["error"] = "błąd";
    }
}
//brak uprawnień - komunikat o błędzie
else
{
    $_SESSION["error"] = "Brak uprawnień do edycji tego zamówienia. Zostało ono stworzone przez: $order[email]";
}
//po wykonaniu skryptu przejdź do ścieżki:
header("location: ../controller/orders.php?orderIdStock=$_SESSION[orderIdStock]");