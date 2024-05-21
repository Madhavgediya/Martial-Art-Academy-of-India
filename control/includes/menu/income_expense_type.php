<li class="treeview <?php
                        if ($c_file == 'add_edit_income_expance_type.php'  || $c_file == 'manage_income_expance_type.php') {
                            echo "active";
                        }
                        ?>">
              <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Income Expance Type</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manage_income_expance_type.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_income_expance_type.php"><i class="fa fa-fw fa-server"></i>Manage Income Expance Type</a></li>
                    <li <?php
                if ($c_file == 'add_edit_income_expance_type.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_income_expance_type.php"><i class="fa fa-fw fa-server"></i> Add Income Expance Type</a></li>

                </ul>
            </li>