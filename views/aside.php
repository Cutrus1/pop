<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="http://127.0.0.1/pop" class="brand-link">
        <span class="brand-text font-weight-light">| Company name</span>
    </a>
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="<?php echo $_SESSION["logged"]["photo"] ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <p style="color: white"><?php echo  $_SESSION["logged"]["firstName"]." ".$_SESSION["logged"]["lastName"] ?></p>
            <br>
            <p style="color: white">Dział: <?php echo $_SESSION["dep"]?></p>
            <p style="color: white"><?php echo "$_SESSION[role]"?></p>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-truck"></i>
                        <p>
                            Purchase Orders
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-chart-line"></i>
                        <p>
                            Charts
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
