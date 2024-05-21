<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b><?php echo SITE_TITLE_SORT; ?></b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?php echo SITE_TITLE_SORT; ?></b></span>
    </a>
    
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
    
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        
        <style>
        .notifications-menu > .dropdown-menu, .navbar-nav > .messages-menu > .dropdown-menu, .navbar-nav > .tasks-menu > .dropdown-menu 
        {
          width:400px !important;
        }
        </style>
        
        <div class="navbar-custom-menu">
        
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <?php echo quick_access();?>
                <?php echo absent_student_boxs(); ?>
               <?php echo bday_reminder_boxs(); ?>
               <?php echo followup_reminder_boxs(); ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?php echo $tmp_name; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->

                        <!-- Menu Body -->

                        <!-- Menu Footer-->
                        <li class="user-footer">
<!--                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>-->
                            <div class="pull-right">
                                <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->

            </ul>
        </div>
    </nav>
</header>
