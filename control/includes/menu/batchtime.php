<li class="treeview <?php
              if ($c_file == 'manage_batchtime.php' || $c_file == 'add_edit_batchtime.php') {
                echo "active";
            }
            ?>">
    <a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Batch Time</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
        if ($c_file == 'manage_batchtime.php') {
            echo 'class="active"';
        }
            ?>><a href="manage_batchtime.php"><i class="fa fa-fw fa-server"></i>Manage Batch Time</a></li>
        <li <?php
    if ($c_file == 'add_edit_batchtime.php') {
        echo 'class="active"';
    }
    ?>><a href="add_edit_batchtime.php"><i class="fa fa-fw fa-server"></i> Add Batch Time</a></li>
    </ul>
</li>