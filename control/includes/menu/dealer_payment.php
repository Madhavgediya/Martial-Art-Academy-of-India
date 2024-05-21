
                
<li class="treeview <?php
                        if ($c_file == 'manage_dealer_payment.php' || $c_file == 'dealer_payment.php' || $c_file == 'report_dealer_payment.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Dealer Payment</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                <li <?php
                if ($c_file == 'report_dealer_payment.php') {
                    echo 'class="active"';
                }
                ?>><a href="report_dealer_payment.php"><i class="fa fa-fw fa-server"></i>Dealer Payment</a></li>
                    <li <?php
                    if ($c_file == 'manage_dealer_payment.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_dealer_payment.php"><i class="fa fa-fw fa-server"></i>Manage Dealer Payment</a></li>
                    <li <?php
                if ($c_file == 'dealer_payment.php') {
                    echo 'class="active"';
                }
                ?>><a href="dealer_payment.php"><i class="fa fa-fw fa-server"></i> Add Dealer Payment</a></li>
                </ul>
            </li>