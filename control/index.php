<?php
include("includes/application_top.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
        <title><?php echo $page_title; ?></title>
        <?php include("includes/include_files.php"); ?>
    </head>
    <body class="skin-green sidebar-mini">
        <div class="wrapper">
            <?php include("includes/header.php"); ?>
            <?php include("includes/left_menu.php"); ?>

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
<!--                        <li class="active">Dashboard</li>-->
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md-12 ml20">
                            Welcome to <?php echo SITE_TITLE_LONG; ?>.</br>
                            Click on navigation bar for more options.
                        </div>
                        <div class="col-md-12 ml20">
                            <?php include("tiles.php");?>
                        </div>
                    </div>
<br/><br/>
<?php if ($tmp_type == 'branch') {  ?>
                    <div class="row">
        <div class="col-md-12">
            <?php include("includes/upcomming-birthday.php"); ?>
          </div></div>
<?php } ?>
                </section>
            </div>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
