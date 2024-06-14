<?php
// controller/ordersApiController.php

// Wczytaj plik z połączeniem do bazy danych
require_once "../model/connect.php";

// Sprawdź, czy żądanie jest metodą GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sprawdź, czy parametr 'status' został przekazany
    if (isset($_GET['status'])) {
        $status = $_GET['status'];

        // Zapytanie do bazy danych lub inna logika pobierania danych
        $sql = "SELECT s.shortcut status, o.status_id, o.id ID, a.name firma 
                FROM `orders` o 
                JOIN statuses s ON o.status_id=s.id 
                JOIN accounts a ON o.account_id=a.id 
                WHERE s.shortcut='$status'";
    }
    else if (isset($_GET['firma'])) {
        $firma = $_GET['firma'];

        // Zapytanie do bazy danych lub inna logika pobierania danych
        $sql = "SELECT s.shortcut status, o.status_id, o.id ID, a.name firma 
                FROM `orders` o 
                JOIN statuses s ON o.status_id=s.id 
                JOIN accounts a ON o.account_id=a.id 
                WHERE a.name='$firma'";
    }
    else {
        // Zapytanie do bazy danych bez warunku wyszukiwania
        $sql = "SELECT s.shortcut status, o.id ID, a.name firma 
                FROM `orders` o 
                JOIN statuses s ON o.status_id=s.id 
                JOIN accounts a ON o.account_id=a.id";
    }
    //var_dump($sql); die;
    $orders_status_result = $conn->query($sql);

    // Sprawdź, czy zapytanie się powiodło
    if ($orders_status_result) {
        // Pobierz dane
        $data = [];

        while ($order = $orders_status_result->fetch_assoc()) {
            $data[] = $order;
        }

        // Zwróć dane w formie JSON
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    } else {
        // Błąd w zapytaniu do bazy danych
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Internal Server Error']);
    }
} else {
    // Nieobsługiwana metoda HTTP (np. POST, PUT, DELETE)
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method Not Allowed']);
}

// Zamknij połączenie z bazą danych
$conn->close();
?>
