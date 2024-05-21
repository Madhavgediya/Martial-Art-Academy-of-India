<?php
if(!session_get("user_name")){
    header('Location: login.php');
}
?>
