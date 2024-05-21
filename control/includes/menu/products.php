<li class="treeview <?php
              if ($c_file == 'manage_products.php' || $c_file == 'add_edit_products.php') {
                echo "active";
            }
            ?>">
    <a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Product</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
        if ($c_file == 'manage_products.php') {
            echo 'class="active"';
        }
            ?>><a href="manage_products.php"><i class="fa fa-fw fa-server"></i>Manage Product</a></li>
        <li <?php
    if ($c_file == 'add_edit_products.php') {
        echo 'class="active"';
    }
    ?>><a href="add_edit_products.php"><i class="fa fa-fw fa-server"></i> Add Product</a></li>
    </ul>
</li>
<li class="treeview <?php
              if ($c_file == 'manage_product_option.php' || $c_file == 'add_edit_product_option.php') {
                echo "active";
            }
            ?>">
    <a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Product Option</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
        if ($c_file == 'manage_product_option.php') {
            echo 'class="active"';
        }
            ?>><a href="manage_product_option.php"><i class="fa fa-fw fa-server"></i>Manage Product O.</a></li>
        <li <?php
    if ($c_file == 'add_edit_product_option.php') {
        echo 'class="active"';
    }
    ?>><a href="add_edit_product_option.php"><i class="fa fa-fw fa-server"></i> Add Product O.</a></li>
    </ul>
</li>