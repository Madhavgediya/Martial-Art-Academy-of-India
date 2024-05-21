<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <!--            <li class="header">MAIN NAVIGATION</li>-->
            <li class="treeview <?php if ($c_file == 'index.php') {
    echo "active";
} ?>">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Home</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>




            <li class="treeview <?php if ($c_file == 'add_edit_results.php' || $c_file == 'manage_results.php') {
    echo "active";
} ?>">
                <a href="#">
                    <i class="fa fa-fw fa-buysellads"></i> <span>Results</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($c_file == 'manage_results.php') {
    echo 'class="active"';
} ?>><a href="manage_results.php"><i class="fa fa-fw fa-buysellads"></i>Manage Results</a></li>
                    <li <?php if ($c_file == 'add_edit_results.php') {
    echo 'class="active"';
} ?>><a href="add_edit_results.php"><i class="fa fa-fw fa-buysellads"></i> Add Results</a></li>
                </ul>
            </li>
  </ul>

</aside>
