<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <title><?php echo $page_title; ?></title>
            <?php
            //Includes CSS and Script
            include("include_files.php");
            $master_file = '';
            ?>
    </head>
    <!-- container Start -->
    <div class="container">

        <!-- Header Nav Bar Start -->
        <!-- use navbar-fixed-top class to fixed nav on top -->
        <?php
        //if session set then menu will be display
        if (session_get("admin_uname")) {
            //set master file for active menu
            if ($c_file == 'manage_user.php' || $c_file == 'add_edit_user.php')
                $master_file = 'manage_user.php';

            if ($c_file == 'project.php' || $c_file == 'add_edit_project.php')
                $master_file = 'project.php';

            if ($c_file == 'change_password.php')
                $master_file = 'change_password.php';

            if ($c_file == 'index.php')
                $master_file = 'index.php';
            ?>
            <div class="navbar navbar-default navbar-inverse" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE_SORT; ?></a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav header_menu">
                            <li><a href="index.php">Home</a></li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">User <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="manage_user.php">Manage User</a></li>
                                    <li><a href="add_edit_user.php">Add User</a></li>
                                </ul>
                            </li>
                            <!--						<li>
                                                                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Project <b class="caret"></b></a>
                                                                                    <ul class="dropdown-menu">
                                                                                            <li><a href="project.php">Manage Project</a></li>
                                                                                            <li><a href="add_edit_project.php">Add Project</a></li>
                                                                                    </ul>
                                                                            </li> -->
                            <li><a href="manage_tbl.php">Manage Case</a></li>
                            <li><a href="crawler.php">Crawler</a></li>
                            <li><a href="change_password.php">Change Password</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div><!-- .nav-collapse -->
                </div>
            </div>
            <?php
        }
        else if (!session_get("admin_uname")) {
            //set master file for active menu
            if ($c_file == 'add_edit_user.php')
                $master_file = 'add_edit_user.php';

            if ($c_file == 'forgot_password.php')
                $master_file = 'forgot_password.php';

            if ($c_file == 'login.php')
                $master_file = 'login.php';
            ?>
            <div class="navbar navbar-default navbar-inverse" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="javascript:void(0);"><?php echo SITE_TITLE_SORT; ?></a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav header_menu">
                            <li><a href="add_edit_user.php">Register</a></li>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="forgot_password.php">Forgot Password</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
            <?php
        }
        ?>
        <!-- Nav Bar End -->
