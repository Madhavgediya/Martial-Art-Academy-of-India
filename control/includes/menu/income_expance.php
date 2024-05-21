<li class="treeview <?php
                        if ($c_file == 'manage_income_expance.php' || $c_file == 'add_edit_income.php'  || $c_file == 'add_edit_expance.php' || $c_file == 'add_edit_income_expance_type.php'  || $c_file == 'manage_income_expance_type.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Income Expance</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manage_income_expance.php' && isset($_REQUEST["pt_type"]) && $_REQUEST["pt_type"]=='Credit') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_income_expance.php?pt_type=Credit"><i class="fa fa-fw fa-server"></i>Manage Income</a></li>
                        <li <?php

                    if ($c_file == 'manage_income_expance.php' && isset($_REQUEST["pt_type"]) && $_REQUEST["pt_type"]=='Debit') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_income_expance.php?pt_type=Debit"><i class="fa fa-fw fa-server"></i>Manage Expance</a></li>
                    <li <?php
                if ($c_file == 'add_edit_income.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_income.php"><i class="fa fa-fw fa-server"></i> Add Income</a></li>
                    <li <?php
                if ($c_file == 'add_edit_expance.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_expance.php"><i class="fa fa-fw fa-server"></i> Add Expance</a></li>


               

                </ul>
            </li>