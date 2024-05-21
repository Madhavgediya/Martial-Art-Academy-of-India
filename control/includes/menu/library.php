<li class="treeview <?php
                        if ($c_file == 'manage_book.php' || $c_file == 'add_edit_book.php'  || $c_file == 'issue_book.php') {
                            echo "active";
                        }
                        ?>">
                <a href="#">
                    <i class="fa fa-fw fa-server"></i> <span>Book</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php
                    if ($c_file == 'manage_book.php') {
                        echo 'class="active"';
                    }
                        ?>><a href="manage_book.php"><i class="fa fa-fw fa-server"></i>Manage Book</a></li>
                    <li <?php
                if ($c_file == 'add_edit_book.php') {
                    echo 'class="active"';
                }
                ?>><a href="add_edit_book.php"><i class="fa fa-fw fa-server"></i> Add Book</a></li>
                <li <?php
                if ($c_file == 'issue_book.php') {
                    echo 'class="active"';
                }
                ?>><a href="issue_book.php"><i class="fa fa-fw fa-server"></i> Issue Book</a></li>
                </ul>
                
            </li>