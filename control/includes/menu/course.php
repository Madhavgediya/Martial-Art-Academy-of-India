<li class="treeview <?php
              if ($c_file == 'manage_course.php' || $c_file == 'add_edit_course.php') {
                echo "active";
            }
            ?>">
    <a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Course</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
        if ($c_file == 'manage_course.php') {
            echo 'class="active"';
        }
            ?>><a href="manage_course.php"><i class="fa fa-fw fa-server"></i>Manage Course</a></li>
        <li <?php
    if ($c_file == 'add_edit_course.php') {
        echo 'class="active"';
    }
    ?>><a href="add_edit_course.php"><i class="fa fa-fw fa-server"></i> Add Course</a></li>
    </ul>
</li>