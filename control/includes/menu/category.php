                    <li class="treeview-submenu <?php
                    if ($c_file == 'add_edit_category.php' || $c_file == 'manage_category.php') {
                        echo "active";
                    }
                    ?>"><a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Category</span><i class="fa fa-angle-left pull-right"></i>
                        <ul class="treeview-menu">
                            <li <?php
                            if ($c_file == 'manage_category.php') {
                                echo 'class="active"';
                            }
                            ?>>
                            <a href="manage_category.php">
                            <i class="fa fa-fw fa-server"></i> <span>Manage Category</span></a></li>
                            <li <?php
                            if ($c_file == 'add_edit_category.php') {
                                echo 'class="active"';
                            }
                            ?>><a href="add_edit_category.php"><i class="fa fa-server"></i> Add Category</a></li>                    
                        </ul>
                    </li>
                    <li class="treeview-submenu <?php
                    if ($c_file == 'add_edit_manufacturer.php' || $c_file == 'manage_manufacturer.php') {
                        echo "active";
                    }
                    ?>"><a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Manufacturer</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                        <ul class="treeview-menu">
                            <li <?php
                            if ($c_file == 'manage_manufacturer.php') {
                                echo 'class="active"';
                            }
                            ?>><a href="manage_manufacturer.php"><i class="fa fa-server"></i> Manage Manufacturer</a></li>
                            <li <?php
                            if ($c_file == 'add_edit_manufacturer.php') {
                                echo 'class="active"';
                            }
                            ?>><a href="add_edit_manufacturer.php"><i class="fa fa-server"></i> Add Manufacturer</a></li>                    
                        </ul>
                    </li>