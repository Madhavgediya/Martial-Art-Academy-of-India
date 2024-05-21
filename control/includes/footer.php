<div class="modal" id="ConfirmDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary confirm-class">Confirm</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<footer class="main-footer">
<!--    <div class="pull-right hidden-xs">
        <b>Version</b> 2.2.0
    </div>-->
<strong>Developed by, <a target="_blank" href="http://aksharecommerce.com">Martial Art Academy of India</a> | All Rights Reserved.</strong>
</footer>
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        /*
         set actuve class to current menu and parent menu		
         */
        $('.header_menu li a').each(function () {
            href = $(this).attr('href');

            if (href == '<?php echo $master_file; ?>')
            {
                $(this).closest('li').addClass('active');
                if ($(this).closest('ul').attr('class') == 'dropdown-menu')
                {
                    $(this).closest('ul').closest('li').addClass('active');
                }
            }

        });
    });
</script>
<div class="modal" id="ErrAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Alert</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="ConfirmAlert">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->