<li class="treeview <?php
              if ($c_file == 'manage_contact.php' || $c_file == 'add_edit_contact.php' || $c_file == 'manage_contact_followup.php' || $c_file == 'add_edit_contact_followup.php') {
                echo "active";
            }
            ?>">
    <a href="#">
        <i class="fa fa-fw fa-server"></i> <span>Inquiry</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li <?php
        if ($c_file == 'manage_contact.php'  || $c_file == 'manage_contact_followup.php' || $c_file == 'add_edit_contact_followup.php') {
            echo 'class="active"';
        }
            ?>><a href="manage_contact.php"><i class="fa fa-fw fa-server"></i>Manage Inquiry</a></li>
        <li <?php
    if ($c_file == 'add_edit_contact.php') {
        echo 'class="active"';
    }
    ?>><a href="add_edit_contact.php"><i class="fa fa-fw fa-server"></i> Add Inquiry</a></li>
    </ul>
</li>