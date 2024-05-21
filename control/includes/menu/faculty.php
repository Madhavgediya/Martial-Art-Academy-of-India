<li class="treeview <?php
                        if ($c_file == 'manage_faculty.php' || $c_file == 'add_edit_faculty.php'|| $c_file == 'faculty_attendance.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Faculty</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manage_faculty.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_faculty.php"><i class="fa fa-fw fa-server"></i>Manage Faculty</a></li>
                    <li <?php
                if ($c_file == 'add_edit_faculty.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_faculty.php"><i class="fa fa-fw fa-server"></i> Add Faculty</a></li>
                  <li <?php
                if ($c_file == 'faculty_attendance.php') {
                    echo 'class="active"';
                }
                ?>><a href="faculty_attendance.php"><i class="fa fa-fw fa-server"></i>Faculty Attendance</a></li>
                </ul>
            </li>
            