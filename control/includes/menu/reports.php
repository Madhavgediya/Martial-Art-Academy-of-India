<li class="treeview <?php
                if (in_array($c_file,$arr_report_stock )) {
                    echo "active";
                }
                ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Report</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                if ($c_file == 'report_receipt.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_receipt.php"><i class="fa fa-fw fa-server"></i>Receipt</a></li>
                <li <?php
                if ($c_file == 'report_student_attendance.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_student_attendance.php"><i class="fa fa-fw fa-server"></i>Student Attedance</a></li>
                <li <?php
                if ($c_file == 'report_manage_event.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_manage_event.php"><i class="fa fa-fw fa-server"></i>Event Attedance</a></li>
                <li <?php
                if ($c_file == 'report_factulty_attendance.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_factulty_attendance.php"><i class="fa fa-fw fa-server"></i>Faculty Attendance</a></li>
                <li <?php
                if ($c_file == 'report_stock.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_stock.php"><i class="fa fa-fw fa-server"></i>Stock</a></li>
                <li <?php
                if ($c_file == 'report_income_expance.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_income_expance.php"><i class="fa fa-fw fa-server"></i>Income Expance</a></li>
                <li <?php
                if($c_file=='report_purchase.php'){
                    echo 'class="active"';
                }
                ?>><a href="report_purchase.php"><i class="fa fa-fw fa-server"></i>Purchase Sale</a></li>
                <!-- <li <?php
                if($c_file=='report_sale.php'){
                    echo 'class="active"';
                }
                ?>>-->
                <li>
                <a href="#"><i class="fa fa-inr"></i>Payment</a></li>
                </ul>
            </li>