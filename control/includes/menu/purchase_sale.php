<li class="treeview-submenu <?php
                            if ($c_file == 'add_edit_dealer.php' || $c_file == 'manage_dealer.php') {
                                echo "active";
                            }
                            ?>"><a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Dealer</span><i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
            if ($c_file == 'manage_dealer.php') {
                echo 'class="active"';
            }
            ?>><a href="manage_dealer.php"><i class="fa fa-server"></i> Manage Dealer</a>
        </li>
        <li <?php
            if ($c_file == 'add_edit_dealer.php') {
                echo 'class="active"';
            }
            ?>><a href="add_edit_dealer.php"><i class="fa fa-server"></i> Add Dealer</a>
        </li>
    </ul>
</li>
<li class="treeview-submenu <?php
                            if ($c_file == 'add_edit_customer.php' || $c_file == 'manage_customer.php') {
                                echo "active";
                            }
                            ?>"><a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Customer</span><i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
            if ($c_file == 'manage_customer.php') {
                echo 'class="active"';
            }
            ?>><a href="manage_customer.php"><i class="fa fa-server"></i> Manage Customer</a></li>
        <li <?php
            if ($c_file == 'add_edit_customer.php') {
                echo 'class="active"';
            }
            ?>><a href="add_edit_customer.php"><i class="fa fa-server"></i> Add Customer</a></li>
    </ul>
</li>
<li class="treeview-submenu <?php
                            if ($c_file == 'add_edit_purchase.php' || $c_file == 'manage_purchase.php' || $c_file == 'manage_return_purchase.php' || $c_file == 'manage_return_item_purchase.php') {
                                echo "active";
                            }
                            ?>"><a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Purchase</span><i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
            if ($c_file == 'manage_purchase.php') {
                echo 'class="active"';
            }
            ?>><a href="manage_purchase.php"><i class="fa fa-server"></i> Manage Purchase</a>
        </li>
        <li <?php
            if ($c_file == 'add_edit_purchase.php') {
                echo 'class="active"';
            }
            ?>><a href="add_edit_purchase.php"><i class="fa fa-server"></i> Add Purchase</a>
        </li>
        <li <?php
            if ($c_file == 'manage_return_purchase.php') {
                echo 'class="active"';
            }
            ?>><a href="manage_return_purchase.php"><i class="fa fa-server"></i> Return Purchase</a>
        </li>
    </ul>
</li>
<li class="treeview-submenu <?php
                            if ($c_file == 'add_edit_sale.php' || $c_file == 'manage_sale.php' || $c_file == 'manage_return_sale.php' || $c_file == 'manage_return_item_sale.php') {
                                echo "active";
                            }
                            ?>"><a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Sale</span><i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
            if ($c_file == 'manage_sale.php') {
                echo 'class="active"';
            }
            ?>><a href="manage_sale.php"><i class="fa fa-server"></i> Manage Sale</a>
        </li>
        <li <?php
            if ($c_file == 'add_edit_sale.php') {
                echo 'class="active"';
            }
            ?>><a href="add_edit_sale.php"><i class="fa fa-server"></i> Add Sale</a>
        </li>
        <li <?php
            if ($c_file == 'manage_return_sale.php') {
                echo 'class="active"';
            }
            ?>><a href="manage_return_sale.php"><i class="fa fa-server"></i> Return Sale</a>
        </li>
    </ul>
</li>
<!--
                    <li class="treeview-submenu <?php
                                                if ($c_file == 'report_stock.php') {
                                                    echo "active";
                                                }
                                                ?>"><a href="report_stock.php">
                    <i class="fa fa-fw fa-server"></i> <span>Stock Report</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    </li>
                    -->