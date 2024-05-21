<li class="treeview <?php
                if ($c_file == 'manage_student.php' || $c_file == 'add_edit_student.php' ||  $c_file == 'student_fees.php'  ||  $c_file == 'student_attendance.php'  ||  $c_file == 'student_document.php' ||  $c_file == 'student_birthday.php' ) {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Student</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                if ($c_file == 'manage_student.php') {
                    echo 'class="active"';
                }
                ?>><a href="manage_student.php"><i class="fa fa-fw fa-server"></i>Manage Student</a></li>
                    <li <?php
                if ($c_file == 'add_edit_student.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_student.php"><i class="fa fa-fw fa-server"></i> Add Student</a></li>
                    <li <?php
                if ($c_file == 'student_fees.php') {
                    echo 'class="active"';
                }
                ?>><a href="student_fees.php"><i class="fa fa-fw fa-server"></i> Student Fee</a></li>
                    <li <?php
                if ($c_file == 'student_attendance.php') {
                    echo 'class="active"';
                }
                ?>><a href="student_attendance.php"><i class="fa fa-fw fa-server"></i> Student Attendance</a></li>
                    <li <?php
                if ($c_file == 'student_birthday.php') {
                    echo 'class="active"';
                }
                ?>><a href="student_birthday.php"><i class="fa fa-fw fa-birthday-cake"></i> Student Birthday</a></li>
                  <li <?php
                if ($c_file == 'manage_exam.php' || $c_file == 'exam_student_entrolled_print.php') {
                    echo 'class="active"';
                }
                ?>><a href="manage_exam.php"><i class="fa fa-fw fa-server"></i>Manage Exam</a></li>
                     <li <?php
                if ($c_file == 'add_edit_exam.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_exam.php"><i class="fa fa-fw fa-server"></i> Add Exam</a></li>
                </ul>
            </li>

                