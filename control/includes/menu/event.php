<li class="treeview <?php
                if ( $c_file == 'manage_event.php' || $c_file == 'add_edit_event.php'  || $c_file == 'event_student_entrolled.php'  ) {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Event</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                <li <?php
                if ($c_file == 'manage_event.php') {
                    echo 'class="active"';
                }
                ?>><a href="manage_event.php"><i class="fa fa-fw fa-server"></i>Manage Event</a></li>
                    <li <?php
                if ($c_file == 'add_edit_event.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_event.php"><i class="fa fa-fw fa-server"></i> Add Event</a></li>

                </ul>
            </li>
            <li class="treeview <?php
                if ($c_file == 'manage_event_others.php' || $c_file == 'add_edit_event_others.php') {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Event Others</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                <li <?php
                if ($c_file == 'manage_event_others.php') {
                    echo 'class="active"';
                }
                ?>><a href="manage_event_others.php"><i class="fa fa-fw fa-server"></i>Manage Event O.</a></li>
                    <li <?php
                if ($c_file == 'add_edit_event_others.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_event_others.php"><i class="fa fa-fw fa-server"></i> Add Event O.</a></li>
                </ul>
            </li>