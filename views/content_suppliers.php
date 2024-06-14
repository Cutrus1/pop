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
                        <li class="breadcrumb-item"><a href="/localhost/pop">Home</a></li>
                        <li class="breadcrumb-item active">Create supplier</li>
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
                        //aktualizacja zamówienia
                        
                        create_suppliers();
                        
                        ?>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">DataTable with suppliers</h3>
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
                                    <th>Supplier id</th>
                                    <th>Supplier name</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT * FROM `suppliers`";
                                $suppliers_results = $conn->query($sql);
                                if ($suppliers_results->num_rows == 0){
                                    echo "<tr><td colspan='6'>There is no suppliers</td></tr>";
                                }else{
                                    while($supplier = $suppliers_results->fetch_assoc()){
                                            echo <<< TABLE
                                            <tr>
                                                <td>$supplier[id]</td>
                                                <td>$supplier[name]</td>
                                            </tr>
                                        TABLE;
                                        }
                                    }

                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Supplier id</th>
                                    <th>Supplier name</th>
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
            </div>

        </div>
            <!-- /.row -->
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->