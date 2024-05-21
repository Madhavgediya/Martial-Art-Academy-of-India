<?php
if ($errormsg != '') {
    ?>			
    <script type="text/javascript" language="javascript">
        $.jGrowl("<strong></strong>", {
            theme: 'danger'
        });
    </script>		
    <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    <?php echo $errormsg; ?>
                  </div>
    <?php
} else if ($successmsg != '') {
    ?>
    <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
                    <?php echo $successmsg; ?>
                  </div>
    <?php
}
?>