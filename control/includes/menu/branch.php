<li class="treeview <?php
            if ($c_file == 'manage_branch.php' || $c_file == 'add_edit_branch.php') {
                echo "active";
            }
            ?>">
                    <a href="#">
                        <i class="fa fa-fw fa-server"></i> <span>Branch</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li <?php
                            if ($c_file == 'manage_branch.php') {
                                echo 'class="active"';
                            }
                            ?>><a href="manage_branch.php"><i class="fa fa-fw fa-server"></i>Manage Branch</a></li>
                        <li <?php
                            if ($c_file == 'add_edit_branch.php') {
                                echo 'class="active"';
                            }
                            ?>><a href="add_edit_branch.php"><i class="fa fa-fw fa-server"></i> Add Branch</a></li>
                    </ul>
                </li>