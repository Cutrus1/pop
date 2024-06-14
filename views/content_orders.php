<?php
require_once "../model/connect.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchase Orders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Order</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <?php
                        //jeśli w adresie URL jest ustawiona zmnienna orderIdApprove to:
                        if (isset($_GET['addOrderId']))
                        {
                            new_order();
                        }
                        //AKCEPTACJA ZAMÓWIENIA
                        //jeśli w adresie URL jest ustawiona zmnienna orderIdApprove to:
                        if (isset($_GET["orderIdApprove"])) {
                            approve_order();
                        }
                        //aktualizacja zamówienia
                        order();
                        ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-9 col-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">DataTable with orders</h3>
                        </div>
                        <div class="card-body">
                            <?php
                            //Miejsce na wyświetlanie komunikatów informacyjnych lub o błędach
                                    if (isset($_SESSION["error"])) {
                                        echo <<< ERROR
                                        <div class="callout callout-info">
                                          <h5>$_SESSION[error]</h5>
                                        </div>
                                        ERROR;
                                    }
                                    unset($_SESSION["error"]);
                                    ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Order id</th>
                                    <th>Order Status</th>
                                    <th>Account name</th>
                                    <th>Order value</th>
                                    <th>Created by</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $orders_status_result = $conn->query($sql_table);
                                if ($orders_status_result->num_rows == 0){
                                    echo "<tr><td colspan='6'>There is no orders</td></tr>";
                                }else{
                                    while($order = $orders_status_result->fetch_assoc()){
                                        switch ($order['status']) {
                                            case 'REQ':
                                                $background='bg-grey';
                                                $action = 'Zatwierdź';
                                                $script = 'orderIdApprove';
                                                break;
                                            case 'APR':
                                                $background='bg-success';
                                                $action = 'Przyjmij na stan';
                                                $script = 'orderIdStock';
                                                break;
                                            case 'PAR':
                                                $background='bg-lightblue';
                                                $action = 'Przyjmij na stan';
                                                $script = 'orderIdStock';
                                                break;
                                            case 'REC':
                                                $background='bg-yellow';
                                                $action = 'Zafakturuj';
                                                $script = 'orderIdInvoice';
                                                break;
                                            case 'INV':
                                                $background='bg-red';
                                                $action = '';
                                                $script = 'orderIdUpdate';
                                                break;
                                        }
                                        if (isset($script)) {
                                            echo <<< TABLE
                                            <tr>
                                                <td>$order[ID]</td>
                                                <td class="$background">$order[status]</td>
                                                <td>$order[KON]</td>
                                                <td>$order[order_value]</td>
                                                <td>$order[email]</td>
                                        <td><a href="./orders.php?$script=$order[ID]">$action</a></td>
                                                <td><a href="./orders.php?orderIdUpdate=$order[ID]">Edit</a></td>
                                            </tr>
                                        TABLE;
                                        }
                                    }
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Order id</th>
                                    <th>Order Status</th>
                                    <th>Account name</th>
                                    <th>Order value</th>
                                    <th>Created by</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <!-- KOLUMNA Z ILOŚCIAMI ZAMÓWIEŃ ZE WZGLĘDU NA TYP-->
                    <a href="../controller/orders.php?status=REQ">
                        <div class="info-box mb-3 bg-grey">
                            <span class="info-box-icon"><i class="fas fa-question"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Requested</span>
                                <span class="info-box-number">
                                <?php
                                echo $_SESSION['COUNT_REQ'];
                                ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </a>
                    <!-- /.info-box -->
                    <a href="../controller/orders.php?status=APR">
                        <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="far fa-check-square"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Aproved</span>
                                <span class="info-box-number">
                                    <?php
                                    echo $_SESSION['COUNT_APR'];
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </a>
                    <!-- /.info-box -->
                    <a href="../controller/orders.php?status=PAR">
                        <div class="info-box mb-3 bg-lightblue">
                            <span class="info-box-icon"><i class="fas fa-business-time"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Part Received</span>
                                <span class="info-box-number">
                                    <?php
                                    echo $_SESSION['COUNT_PAR'];
                                    ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                        <a href="../controller/orders.php?status=REC">
                        <div class="info-box mb-3 bg-yellow">
                            <span class="info-box-icon"><i class="fas fa-box-archive"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Received</span>
                                <span class="info-box-number">
                                    <?php
                                    echo $_SESSION['COUNT_REC'];
                                    ?>
                                    </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </a>
                    <a href="../controller/orders.php?status=INV">
                        <div class="info-box mb-3 bg-red">
                            <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Invoiced</span>
                                <span class="info-box-number">
                                    <?php
                                    echo $_SESSION['COUNT_INV'];
                                    ?>
                                    </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </a>
                    <a href="../controller/orders.php">
                        <div class="info-box mb-3 bg-blue">
                            <span class="info-box-icon"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">ALL</span>
                                <span class="info-box-number">
                                    <?php
                                    echo $_SESSION['COUNT_ALL'];
                                    ?>
                                    </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.card -->
                    </a>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->