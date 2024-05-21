<li class="treeview <?php
              if ($c_file == 'manage_belt.php' || $c_file == 'add_edit_belt.php') {
                echo "active";
            }
            ?>">
    <a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Belt</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
        if ($c_file == 'manage_belt.php') {
            echo 'class="active"';
        }
            ?>><a href="manage_belt.php"><i class="fa fa-fw fa-server"></i>Manage Belt</a></li>
        <li <?php
    if ($c_file == 'add_edit_belt.php') {
        echo 'class="active"';
    }
    ?>><a href="add_edit_belt.php"><i class="fa fa-fw fa-server"></i> Add Belt</a></li>
    </ul>
</li>