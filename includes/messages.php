<?php
if ($errormsg != '') {
    ?>			
    <script type="text/javascript" language="javascript">
        jGrowlMsg("<?php echo $errormsg; ?>", 'danger');
    </script>			
    <?php
} else if ($successmsg != '') {
    ?>				
    <script type="text/javascript" language="javascript">
        jGrowlMsg("<?php echo $successmsg; ?>", 'success');
    </script>			
    <?php
}
?>