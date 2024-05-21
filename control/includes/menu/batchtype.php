<li class="treeview <?php
                        if ($c_file == 'manage_batchtype.php' || $c_file == 'add_edit_batchtype.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Batch Type</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manag_batchtype.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_batchtype.php"><i class="fa fa-fw fa-server"></i>Manage Batch Type</a></li>
                    <li <?php
                if ($c_file == 'add_edit_batchtype.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_batchtype.php"><i class="fa fa-fw fa-server"></i> Add Batch Type</a></li>
                </ul>
            </li>