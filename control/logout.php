<?php
include("includes/application_top.php");
session_unset();
session_destroy();
header("Location:login.php?msg_id=3");
?>
