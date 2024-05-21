<li class="treeview <?php
                if ($c_file == 'manage_exam.php' || $c_file == 'add_edit_exam.php' || $c_file == 'manage_examcategories.php' || $c_file == 'add_edit_examcategories.php'   || $c_file == 'exam_student_entrolled.php'  || $c_file == 'exam_student_entrolled_print.php' ) {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Exams</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
     
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