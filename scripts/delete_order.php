<?php
// Wczytaj plik z połączeniem do bazy danych
require_once "../model/connect.php";

session_start();

// Sprawdź, czy użytkownik jest zalogowany i czy ma odpowiednie uprawnienia (tutaj rola moderatora)
if ($_SESSION["logged"]["role_id"]!='2') {
    
    $_SESSION["error"] = "Brak uprawnień - musisz być moderatorem";
    header("location: ../controller/orders.php");
        exit(); // Upewnij się, że skrypt kończy działanie po przekierowaniu
        
}

// Sprawdź, czy otrzymano identyfikator zamówienia
if(isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Usuń najpierw rekordy z tabeli ordered_items powiązane z tym zamówieniem
    $sql_delete_items = "DELETE FROM ordered_items WHERE order_id = $order_id";
    if ($conn->query($sql_delete_items) === TRUE) {
        // Usuwanie rekordów z ordered_items powiodło się, więc usuń teraz zamówienie
        $sql_delete_order = "DELETE FROM orders WHERE id = $order_id";
        if ($conn->query($sql_delete_order) === TRUE) {
            // Zamówienie zostało pomyślnie usunięte
            $_SESSION["error"] = "Zamówienie zostało pomyślnie usunięte.";
        } else {
            // Błąd podczas usuwania zamówienia
            $_SESSION["error"] = "Wystąpił błąd podczas usuwania zamówienia: " . $conn->error;
        }
    } else {
        // Błąd podczas usuwania rekordów z ordered_items
        $_SESSION["error"] = "Wystąpił błąd podczas usuwania pozycji zamówienia: " . $conn->error;
    }
} else {
    // Jeśli nie otrzymano identyfikatora zamówienia
    $_SESSION["error"] = "Brak identyfikatora zamówienia.";
}


// Sprawdź, czy otrzymano identyfikator zamówienia
if(isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Zapytanie SQL do usunięcia zamówienia z bazy danych
    $sql = "DELETE FROM orders WHERE id = $order_id";

    // Wykonaj zapytanie
    if ($conn->query($sql) === TRUE) {
        // Zamówienie zostało pomyślnie usunięte
        header("Location: ../controller/orders.php");
    } else {
        // Błąd podczas usuwania zamówienia
        echo "Wystąpił błąd podczas usuwania zamówienia: " . $conn->error;
    }
} else {
    // Jeśli nie otrzymano identyfikatora zamówienia
    echo "Brak identyfikatora zamówienia.";
}

// Zamknij połączenie z bazą danych
$conn->close();
?>
