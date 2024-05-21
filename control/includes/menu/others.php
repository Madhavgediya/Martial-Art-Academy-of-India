<li class="treeview <?php
                if (in_array($c_file,$arr_others )) {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Others</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                if ($c_file == 'pending_fees.php') {
                    echo 'class="active"';
                }
                ?>><a href="pending_fees.php"><i class="fa fa-fw fa-server"></i>Pending Fee</a></li>
                    <li <?php
                if ($c_file == 'student_missing_details.php') {
                    echo 'class="active"';
                }
                ?>><a href="student_missing_details.php"><i class="fa fa-fw fa-server"></i>Missing Details</a></li>


                </ul>
            </li>