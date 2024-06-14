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

$sql = "SELECT name FROM `items` WHERE id = $_POST[item_id];";
$result = $conn->query($sql);
$item = $result->fetch_assoc();
$item_name = $item['name'];


//sprawdzene ostatniego numeru zamówienia
$sql2 = "SELECT max(order_id) last_order FROM `ordered_items`;";
$result = $conn->query($sql2);
$last = $result->fetch_assoc();
//$last['last_order'] = $_SESSION['last']+1;




//dodanie pozycji
$sql3 = "INSERT INTO `ordered_items` (`id`, `order_id`, `item_id`, `unit_prize`, `quantity`) VALUES (NULL, $_SESSION[order_number], '$_POST[item_id]', '$_POST[unit_price]', '$_POST[quantity]');";
$conn->query($sql3);
if ($conn->affected_rows == 0) {
    $_SESSION["error"] = "błąd";
}
else
{
    $_SESSION["error"] = "Do zamówienia $_SESSION[order_number] dodano $_POST[quantity]x $item[name] $_POST[unit_price]zł za szt.";
}
header("location: ../controller/orders.php?addOrderId=1");
