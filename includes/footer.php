	<!-- Footer Start -->
	<div class="footer">
		<div class="container">
			<p>Copyright &cop; 2006-2014 AE, Website Development Company | All Rights Reserved.</p>
		</div>
	</div>		
	<!-- Footer End -->
</div><!-- container End -->
<script type="text/javascript">
	$(document).ready(function() 
	{
		/*
		set actuve class to current menu and parent menu		
		*/
        $('.header_menu li a').each(function() {
            href = $(this).attr('href');

            if (href == '<?php echo $master_file; ?>')
            {
                $(this).closest('li').addClass('active');
				if($(this).closest('ul').attr('class')=='dropdown-menu')
				{
					$(this).closest('ul').closest('li').addClass('active');
				}
            }

        });
    });
</script>
</body>
</html>