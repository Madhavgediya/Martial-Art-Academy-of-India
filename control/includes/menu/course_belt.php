<li class="treeview <?php
                        if ($c_file == 'manage_account.php' || $c_file == 'add_edit_account.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Account</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manage_account.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_account.php"><i class="fa fa-fw fa-server"></i>Manage Account</a></li>
                    <li <?php
                if ($c_file == 'add_edit_account.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_account.php"><i class="fa fa-fw fa-server"></i> Add Account</a></li>
                </ul>
            </li>
<li class="treeview <?php
                        if ($c_file == 'manage_course_belt.php' || $c_file == 'add_edit_course_belt.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Course Belt</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manage_course_belt.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_course_belt.php"><i class="fa fa-fw fa-server"></i>Manage Course Belt</a></li>
                    <li <?php
                if ($c_file == 'add_edit_course_belt.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_course_belt.php"><i class="fa fa-fw fa-server"></i> Add Course Belt</a></li>
                </ul>
            </li>