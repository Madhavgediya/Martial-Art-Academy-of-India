<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WebService For Nail Art</title>
<head>
<script type="text/javascript" src="../js/jquery.min.js"></script>
    <script language="javascript">
        onload = function() {
    }
</script>
<?php
$action_url = "http://maccess.martialartacademyofindia.com/webservice/webservice.php";
if ($_SERVER["SERVER_NAME"] == 'localhost')
{
	$action_url = "http://localhost/martialart-admin/webservice.php";
}
?>
</head>

<body>
	<div class="mainContainer" >
	<!-- http://localhost/nailart/webservice/webservice.php -->
 <form id="form2" name="form2" action="<?php echo $action_url; ?>" target="_blank" method="post">
	Json <input type="txt" id="json" name="json" value="" >
	<input type="submit" value="Check it" />
	</form>
Login:	 {"name":"login","gr_no":"MA00000001","password":"Mayur12@"}
Branch:  {"name":"get_branch_details"}
</div>
</body>
</html> 