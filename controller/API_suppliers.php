<?php
// controller/ordersApiController.php

// Wczytaj plik z połączeniem do bazy danych
require_once "../model/connect.php";

// Sprawdź, czy żądanie jest metodą GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Zapytanie do bazy danych
    $sql = "SELECT id, name FROM suppliers";

    // Wykonaj zapytanie
    $result = $conn->query($sql);

    // Sprawdź, czy zapytanie się powiodło
    if ($result) {
        // Pobierz dane
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
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
