<li class="treeview <?php
                if ($c_file == 'manage_examcategories.php' || $c_file == 'add_edit_examcategories.php' ) {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Exam Category</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                 <ul class="treeview-menu">
     <?php if ($tmp_admin_id == 1) {  ?>           
                    <li <?php
                if ($c_file == 'manage_examcategories.php') {
                    echo 'class="active"';
                }
                ?>><a href="manage_examcategories.php"><i class="fa fa-fw fa-server"></i>Manage Exam Categories</a></li>
                    <li <?php
                if ($c_file == 'add_edit_examcategories.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_examcategories.php"><i class="fa fa-fw fa-server"></i> Add Exam Categories</a></li>
     <?php } ?>      
               

                </ul>
            </li>