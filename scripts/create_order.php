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
$LOGGED_USER_ID = $_SESSION["logged"]["ID"];

//sprawdzene ostatniego numeru zamówienia
$sql2 = "SELECT max(id) last_order FROM `orders`;";
$result = $conn->query($sql2);
$last = $result->fetch_assoc();
//$_SESSION['last_order']=$last['last_order'] + 1;
$_SESSION['order_number'] = $last['last_order'] + 1;

//stworzenie zamówienia
$sql = "INSERT INTO `orders` (`id`, `account_id`, `status_id`, `created_by`, `created_at`) VALUES ($_SESSION[order_number], '$_POST[account_id]', '1',$LOGGED_USER_ID,current_timestamp());";
$conn->query($sql);
if ($conn->affected_rows == 0){
    header("location: ../controller/orders.php?addOrder=0");
}else{
    $_SESSION["error"]="Stworzono zamówienie $_SESSION[order_number]";
    header("location: ../controller/orders.php?addOrderId=1");
}

