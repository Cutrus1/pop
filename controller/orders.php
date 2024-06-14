<?php
require_once "../model/connect.php";

session_start();

//wylogowanie użytkownika po wygaśnięciu sesji (status !=2)
if (!isset($_SESSION["logged"]) || session_status() != 2) {
    $_SESSION["error"] = "Zaloguj się!";
    header("location: ../");
} else {
    //przypisanie do zmiennej roli użytkownika
    switch ($_SESSION["logged"]["role_id"]) {
        case 1:
            $_SESSION["role"] = "Użytkownik";
            break;
        case 2:
            $_SESSION["role"] = "Moderator";
            break;
        case 3:
            $_SESSION["role"] = "Administrator";
            break;
        case 4:
            $_SESSION["role"] = "Księgowy";
            break;
    }
    switch ($_SESSION["logged"]["departament"]) {
        case 1:
            $_SESSION["dep"] = "IT";
            break;
        case 2:
            $_SESSION["dep"] = "H&S";
            break;
        case 3:
            $_SESSION["dep"] = "Administracja";
            break;
    }
}

if (isset($_SESSION["logged"]["last_activity"])) {
    $lastActivityTime = $_SESSION["logged"]["last_activity"];
    $currentTime = time();
    $sessionTimeout = 900; //15 minut (15 * 60s)

    if ($currentTime - $lastActivityTime > $sessionTimeout) {
        $_SESSION["error"] = "Sesja użytkownika wygasła!";
        unset($_SESSION["logged"]);
        header("location: ../");
        exit();
    }
} else {
    $_SESSION["error"] = "Sesja użytkwonika wygasła lub nie jest aktywna!";
    header("location: ../");
}

//OBLICZANIE ILOŚCI ZAMÓWIEŃ ZE WZGLĘDU NA TYP
$sql = "SELECT s.shortcut SHORTCUT, count(o.status_id) licznik FROM `statuses` s LEFT JOIN orders o ON s.id = o.status_id GROUP BY s.id;";
$result = $conn->query($sql);
while($order = $result->fetch_assoc())
{
    $_SESSION["COUNT_" . $order['SHORTCUT']] = $order['licznik'];
}
/*
$sql = "SELECT count(*) REQ FROM `orders` WHERE status_id=1;";
$result = $conn->query($sql);
while($order = $result->fetch_assoc())
{
    $_SESSION['COUNT_REQ'] = $order['REQ'];
}
$sql = "SELECT count(*) APR FROM `orders` WHERE status_id=2;";
$result = $conn->query($sql);
while($order = $result->fetch_assoc()){
    $_SESSION['COUNT_APR'] = $order['APR'];
}
$sql = "SELECT count(*) PAR FROM `orders` WHERE status_id=3;";
$result = $conn->query($sql);
while($order = $result->fetch_assoc()){
    $_SESSION['COUNT_PAR'] = $order['PAR'];
}
$sql = "SELECT count(*) REC FROM `orders` WHERE status_id=4;";
$result = $conn->query($sql);
while($order = $result->fetch_assoc())
{
    $_SESSION['COUNT_REC'] = $order['REC'];
}
$sql = "SELECT count(*) INV FROM `orders` WHERE status_id=5;";
$result = $conn->query($sql);
while($order = $result->fetch_assoc())
{
    $_SESSION['COUNT_INV'] = $order['INV'];
}*/

//Obliczanie łącznej liczby zamówień
$sql = "SELECT count(*) all_orders FROM `orders`";
$result = $conn->query($sql);
while($order = $result->fetch_assoc())
{
    $_SESSION['COUNT_ALL'] = $order['all_orders'];
}

//jeśli w adresie URL jest ustawiona zmienna GET to zmodyfikuj zapytanie
if (isset($_GET["status"]))
{
    $sql_table = "SELECT o.id ID, s.shortcut status, a.name KON, SUM(oi.unit_prize*oi.quantity) order_value, u.email FROM `ordered_items` oi JOIN orders o ON o.id=oi.order_id JOIN suppliers a ON o.account_id=a.id JOIN statuses s ON o.status_id=s.id JOIN users u ON u.id=o.created_by WHERE s.shortcut='$_GET[status]' GROUP BY ID DESC;";
}
elseif (isset($_GET['orderIdUpdate']))
{
    $sql_table = "SELECT o.id ID, s.shortcut status, a.name KON, SUM(oi.unit_prize*oi.quantity) order_value, u.email FROM `ordered_items` oi JOIN orders o ON o.id=oi.order_id JOIN suppliers a ON o.account_id=a.id JOIN statuses s ON o.status_id=s.id JOIN users u ON u.id=o.created_by WHERE o.id='$_GET[orderIdUpdate]';";
}
elseif (isset($_GET['orderIdStock']))
{
    $sql_table = "SELECT o.id ID, s.shortcut status, a.name KON, SUM(oi.unit_prize*oi.quantity) order_value, u.email FROM `ordered_items` oi JOIN orders o ON o.id=oi.order_id JOIN suppliers a ON o.account_id=a.id JOIN statuses s ON o.status_id=s.id JOIN users u ON u.id=o.created_by WHERE o.id='$_GET[orderIdStock]';";
}
elseif (isset($_GET['addOrderId']) and $_GET['addOrderId']>0)
{
    $sql_table = "SELECT o.id ID, s.shortcut status, a.name KON, SUM(oi.unit_prize*oi.quantity) order_value, u.email FROM `ordered_items` oi RIGHT JOIN orders o ON o.id=oi.order_id JOIN suppliers a ON o.account_id=a.id JOIN statuses s ON o.status_id=s.id JOIN users u ON u.id=o.created_by WHERE o.id='$_SESSION[order_number]';";
}
//jeśli nie to wyświetlaj wszystkie zamówienia
else
{
    $sql_table = "SELECT o.id ID, s.shortcut status, a.name KON, SUM(oi.unit_prize*oi.quantity) order_value, u.email FROM `ordered_items` oi RIGHT JOIN orders o ON o.id=oi.order_id JOIN suppliers a ON o.account_id=a.id JOIN statuses s ON o.status_id=s.id JOIN users u ON u.id=o.created_by GROUP BY ID ORDER BY ID DESC;";
}
function calculate_budget(){
    //require_once "../scripts/connect.php";
    //DLACZEGO NIE DZIAŁA require_once i trzeba definować $conn ???
    $conn = new mysqli("localhost", "root", "", "pop_system");
    $logged_user_dep = $_SESSION['logged']['departament'];
    $sql = "SELECT budget FROM `departaments` WHERE id = $logged_user_dep;";
    $result = $conn->query($sql);
    $departament = $result->fetch_assoc();
    $budget_base = $departament['budget'];

    $sql = "SELECT o.id ID, SUM(oi.unit_prize*oi.quantity) budget_used, u.email, d.id DEP FROM `ordered_items` oi JOIN orders o ON o.id=oi.order_id JOIN users u ON u.id=o.created_by JOIN departaments d ON d.id=u.dep_id WHERE d.id=$logged_user_dep;";
    $result = $conn->query($sql);
    $departament = $result->fetch_assoc();

    $_SESSION["budget_left"] = $budget_base - $departament['budget_used'];
}
function new_order(){
    //NOWE ZAMÓWIENIE
    if($_GET['addOrderId']==0)
    {
        calculate_budget();
        if ($_SESSION["budget_left"]>0)
        {
            echo <<< NEW_ORDER
                            <div class="card-header">
                                <h3 class="card-title">Stwórz zamówienie</h3>
                            </div>
                            <div class="card-body">
                                <form action="../scripts/create_order.php" method="post">
                                    <label for="account_id">Firma: </label>
                                    <select name="account_id" autofocus>
                            NEW_ORDER;
            require_once "../scripts/connect.php";
            $sql = "SELECT id, name FROM suppliers";
            $result = $conn->query($sql);
            while ($acc = $result->fetch_assoc()) {
                echo "<option value=$acc[id]>$acc[name]</option>";
            }
            echo <<< NEW_ORDER
                                </select><br>
                                <input type="submit" value="Stwórz zamówienie">
                            </form>
                        </div>
                        <!-- /.card-body -->
NEW_ORDER;
        }
        else{
            $_SESSION["error"] = "Wyczerpano budżet";
        }
    }
    else if($_GET['addOrderId']==1){
        calculate_budget();
        if ($_SESSION["budget_left"]>0) {
            echo <<< NEW_ORDER
                        <div class="card-header">
                            <h3 class="card-title">Dodaj pozycję do zamówienia: $_SESSION[order_number]</h3>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">Budżet działu wynosi: $_SESSION[budget_left] </h3>
                        </div>
                        <div class="card-body">
                            <form action="../scripts/order_items.php" method="post" class="col-6">
                                <label for="item_id">Co chcesz zamówić? : </label>
                                <select name="item_id">
NEW_ORDER;
            require_once "../scripts/connect.php";
            $sql = "SELECT id, name FROM items";
            $result = $conn->query($sql);
            while ($ite = $result->fetch_assoc()) {
                echo "<option value=$ite[id]>$ite[name]</option>";
            }
            echo <<< NEW_ORDER

                                </select>
                                <input type="number" class="form-control" placeholder="Ile?" name="quantity" autofocus>
                                <input type="number" step="0.01" class="form-control" placeholder="Podaj kwotę jednostkową?" name="unit_price" max="$_SESSION[budget_left]">
                                </select><br>
                                <input type="submit" value="Dodaj pozycję">
                            </form>
                            <br>
                            <a href="http://127.0.0.1/pop/controller/orders.php">Zakończ</a>
                        </div>
                        <!-- /.card-body -->
              NEW_ORDER;
        }
        else{
            $_SESSION["error"] = "Wyczerpano budżet";
        }
    }
}

function approve_order(){
    //zapisanie zmniennej GET do zmniennej sesyjnej aby była dostępna w skrypcie approve_order.php
    $_SESSION["orderIdApprove"] = $_GET["orderIdApprove"];
    ECHO <<< FORM
    <form id="onclick_form" action="../scripts/approve_order.php" method="post">
    </form>
    <!-- funkcja JS, która powoduje wysłanie formularza po kliknięciu.
    Formularz uruchamia skrypt approve_order.php-->
    <script type="text/javascript">
        document.getElementById('onclick_form').submit(); // SUBMIT FORM
    </script>
    FORM;
}


function order(){

    //PRZYJĘCIE NA STAN
    require_once "../scripts/connect.php";
    if (isset($_GET["orderIdStock"])) {
        $_SESSION["orderIdStock"] = $_GET["orderIdStock"];
        echo <<< STOCK_ORDER
            <div class="card-header">
                <h3 class="card-title">Przyjęcie na stan zamówienia ID: $_SESSION[orderIdStock]</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Item</th>
                            <th>Unit pice</th>
                            <th>Quantity</th>
                            <th>delivered</th>
                            <th>Progress</th>
                            <th style="width: 40px">Label</th>
                            <th>Podaj stan faktyczny</th>
                        </tr>
                    </thead>
                    <tbody>
STOCK_ORDER;
        $sql = "SELECT oi.id ID, name, unit_prize, quantity, delivered_quantity, unit_prize FROM `ordered_items` oi JOIN items i ON oi.item_id = i.id WHERE order_id = $_GET[orderIdStock];";
        $result = $conn->query($sql);
        if ($result->num_rows == 0)
        {
            echo "<tr><td colspan='6'>There is no orders</td></tr>";
        }
        else {
            while ($order_stock = $result->fetch_assoc()) {
                //Obliczanie procentowego dostarczenia zamówienia
                //zmienna $progress jest wykorzystana dla stylu "paska postępu"
                $progress = $order_stock['delivered_quantity']/$order_stock['quantity']*100;
                echo <<< STOCK_ORDER
                    <tr>
                        <td>$order_stock[ID]</td>
                        <td>$order_stock[name]</td>
                        <td>$order_stock[unit_prize]</td>
                        <td>$order_stock[quantity]</td>
                        <td>$order_stock[delivered_quantity]</td>
                        <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width:$progress%"></div>
                        </div>
                        </td>
                        <td><span class="badge bg-info">$progress %</span></td>
                STOCK_ORDER;
                //jeśli nie dostarczono całości będzie wyświetlany formularz z możliwością dodawania
                if ($order_stock['quantity']>$order_stock['delivered_quantity'])
                {
                    echo <<< STOCK_ORDER
                <form action="../scripts/stock_order.php?ID=$order_stock[ID]" method="post">
                        <td><input name="item" type="number" min="$order_stock[delivered_quantity]" max="$order_stock[quantity]">
                        <input type="submit" value="Przyjmij"></td>
                    </form>
                    </tr>
                STOCK_ORDER;
                }
                //jeśli dostarczono wszystko to nie wyświetlaj formularza
                else{
                    echo"<td>Przyjęto</td>";
                }
            }
            echo <<< STOCK_ORDER
                  </tbody>
                </table>
              </div>
            STOCK_ORDER;
        }
    }

    //ZAFAKTUROWANIE
    if (isset($_GET["orderIdInvoice"])) {
        $_SESSION["orderIdInvoice"] = $_GET["orderIdInvoice"];
        ?>
        <form id="onclick_form" action="../scripts/invoice_order.php" method="post">
        </form>
        <script type="text/javascript">
            document.getElementById('onclick_form').submit(); // SUBMIT FORM
        </script>
        <?php
    }

    //EDYCJA (MODERATOR)
    if (isset($_GET["orderIdUpdate"]))
    {
        $_SESSION["orderIdUpdate"] = $_GET["orderIdUpdate"];
        $sql = "SELECT s.name status, o.status_id status_id, o.id ID, a.name account_name, a.id account_id FROM `orders` o JOIN statuses s ON o.status_id=s.id JOIN suppliers a ON o.account_id=a.id WHERE o.id = '$_GET[orderIdUpdate]'";
        $result = $conn->query($sql);
        $updateOrder = $result->fetch_assoc();
        echo <<< EDIT_ORDER
            <div class="card-header">
                <h3 class="card-title">Aktualizacja zamówienia ID: $_SESSION[orderIdUpdate]</h3>
            </div>
            <div class="card-body">
                <form action="../scripts/update_order.php" method="post">
                    <label for="account_id">Konto: </label>
                    <select name="account_id" autofocus>
                        <option value="$updateOrder[account_id]">$updateOrder[account_name]</option>
    EDIT_ORDER;
                $sql = "SELECT id, name FROM suppliers";
                $result = $conn->query($sql);
                while ($acc = $result->fetch_assoc()) {
                    echo "<option value=$acc[id]>$acc[name]</option>";
                }
                echo <<< EDIT_ORDER
                    </select>
                    <br>
                    <label for="status_id">Status: </label>
                    <select name="status_id">
                        <option value="$updateOrder[status_id]">$updateOrder[status]</option>
EDIT_ORDER;
                $sql = "SELECT id, name FROM statuses";
                $result = $conn->query($sql);
                while ($sta = $result->fetch_assoc()) {
                    echo "<option value=$sta[id]>$sta[name]</option>";
                }
                echo <<< EDIT_ORDER
                    </select>
                    <br>
                    <input type="submit" value="Zmień dane zamówienia">
                </form>
                <br><br>
                <!-- Dodaj formularz do usuwania zamówienia -->
                <form action="../scripts/delete_order.php" method="post">
                    <input type="hidden" name="order_id" value="$_SESSION[orderIdUpdate]">
                    <input type="submit" value="Usuń zamówienie">
                </form>
                                </div>
                EDIT_ORDER;
    }
}

// Load the view
include "../views/ViewOrders.php";