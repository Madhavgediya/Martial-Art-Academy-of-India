<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WebService For Nail Art</title>
<head>
<script type="text/javascript" src="../js/jquery.min.js"></script>
    <script language="javascript">
        onload = function() {
    }
</script>
<style type="text/css">
  .mainContainer {
	width: 100%;
	font-size: 12px;
	font-family: verdana;
  }

  .fl {
  	float: left;
  }

  .w500 {
  	width: 500px;
  }

  .clear {
  	clear: both;
  }


  .box {
  	border: 1px solid lightgray;
  	-moz-border-radius: 4px;
  	padding: 5px;
  	margin: 20px;
      margin-left: 400px;

  }

  .box .header {
  	background: none repeat scroll 0 0 #EEEEEE;
  	border-top-left-radius: 4px;
  	border-top-right-radius: 4px;
  	color: grey;
  	font-family: verdana;
  	font-size: 14px;
  	font-weight: bold;
  	margin-bottom: 5px;
  	padding: 5px;
  	text-align: center;
  }

  .box .cont {
  	padding: 5px;
  }

  .box .reqT,.box .resT {
  	margin-bottom: 10px;
  }
.preview
{
width:200px;
border:solid 1px #dedede;
padding:10px;
}
#preview
{
color:#cc0000;
font-size:12px
}
</style>

<script>
    $(document).ready(function(){
        $('#btnsubmit').click(function(){
		 	var json_body="{}"
			var json_header="{\"name\":\"catnonregistreduser\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
				  	$('#request').html(json_header);
					$('#response').html(response);
				  }
				});
		 });
         $('#btnsubmitcatreg').click(function(){
		 	var json_body="{\"Username\":\""+$('#Username').val()+"\",\"Password\":\""+$('#Password').val()+"\"}"
			var json_header="{\"name\":\"catregistreduser\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
				  	$('#request_submitcatreg').html(json_header);
					$('#response_submitcatreg').html(response);
				  }
				});
		 });

         $('#btnselcatnonregistreduser').click(function(){
		 	var json_body="{\"catid\":\""+$('#noncategory_id').val()+"\"}"
			var json_header="{\"name\":\"selcatregistreduser\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_selcatnonregistreduser').html(json_header);
					$('#response_selcatnonregistreduser').html(response);
				  }
				});
		 });

         $('#btnselcatregistreduser').click(function(){
		 	var json_body="{\"Username\":\""+$('#selcatnonUsername').val()+"\",\"Password\":\""+$('#selcatnonPassword').val()+"\",\"catid\":\""+$('#category_id').val()+"\"}"
			var json_header="{\"name\":\"selcatnonregistreduser\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_selcatregistreduser').html(json_header);
					$('#response_selcatregistreduser').html(response);
				  }
			});
		 });

         $('#btndetailview').click(function(){
		 	var json_body="{\"imageid\":\""+$('#image_id').val()+"\"}"
			var json_header="{\"name\":\"detailview\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_detailview').html(json_header);
					$('#response_detailview').html(response);
				  }
			});
		 });

         $('#btnvideos').click(function(){
		 	var json_body="{}"
			var json_header="{\"name\":\"videos\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_videos').html(json_header);
					$('#response_videos').html(response);
				  }
			});
		 });

          $('#btncaretips').click(function(){
		 	var json_body="{}"
			var json_header="{\"name\":\"caretips\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_caretips').html(json_header);
					$('#response_caretips').html(response);
				  }
			});
		 });

         $('#btnlogin').click(function(){
		 	var json_body="{\"Username\":\""+$('#loginUsername').val()+"\",\"Password\":\""+$('#loginPassword').val()+"\"}"
			var json_header="{\"name\":\"login\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_login').html(json_header);
					$('#response_login').html(response);
				  }
			});
		 });

         $('#btnforgotpassword').click(function(){
		 	var json_body="{\"Username\":\""+$('#forgotpasswordUsername').val()+"\"}"
			var json_header="{\"name\":\"forgotpassword\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_forgotpassword').html(json_header);
					$('#response_forgotpassword').html(response);
				  }
			});
		 });

         $('#btnregistration').click(function(){
		 	var json_body="{\"Firstname\":\""+$('#regFirstname').val()+"\",\"Lastname\":\""+$('#regLastname').val()+"\",\"Email\":\""+$('#regEmail').val()+"\",\"Password\":\""+$('#regPassword').val()+"\"}"
			var json_header="{\"name\":\"registration\",\"body\":"+json_body+"}";
			$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){
                    $('#request_registration').html(json_header);
					$('#response_registration').html(response);
				  }
			});
		 });
    });
  </script>
</head>

<body>
	<div class="mainContainer" >
	<!-- http://localhost/nailart/webservice/webservice.php -->
 <form id="form2" name="form2" action="http://localhost/martialart-admin/webservice.php" target="_blank" method="post">
<!--	<form id="form2" name="form2" action="http://localhost/nailart/webservice/webservice.php" target="_blank" method="post"> -->
	Json <input type="txt" id="json" name="json" value="" >
	<input type="submit" value="Check it" />
	</form>
Login:	 {"name":"login","gr_no":"MA00000001","password":"Mayur12@"}
Branch:  {"name":"get_branch_details"}
        <!-- Category Non-Registerd Users Start -->
       	<div class="fl w500 box">
        	<form name="form_login" id="form_login">
				<div class="header">Category Non-Registerd Users</div>
				<div class="cont">
                    <div>
                        Webservice Name: catnonregistreduser
                    </div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request" name="request" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response" name="response" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnsubmit" name="btnsubmit" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- Category Non-Registerd Users End -->

        <!-- Category Registerd Users Start -->
       	<div class="fl w500 box">
        	<form name="form_submitcatreg" id="form_submitcatreg">
				<div class="header">Category Registerd Users</div>
				<div class="cont">
                    <div>
                        Webservice Name: catregistreduser
                    </div>
                    <div style="font-weight: bold">Username</div>
					<div>
						<input type="text" id="Username" name="Username" />
					</div>
					<div style="font-weight: bold">Password</div>
					<div>
						<input type="text" id="Password" name="Password" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_submitcatreg" name="request_submitcatreg" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_submitcatreg" name="response_submitcatreg" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnsubmitcatreg" name="btnsubmitcatreg" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- Category Registerd Users End -->

        <!-- Selected Category Non-Registerd Users Start -->
       	<div class="fl w500 box">
        	<form name="form_selcatnonregistreduser" id="form_selcatnonregistreduser">
				<div class="header">Selected Category Non-Registerd Users</div>
				<div class="cont">
                    <div>
                        Webservice Name: selcatnonregistreduser
                    </div>
                    <div style="font-weight: bold">Category ID</div>
					<div>
						<input type="text" id="noncategory_id" name="noncategory_id" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_selcatnonregistreduser" name="request_selcatnonregistreduser" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_selcatnonregistreduser" name="response_selcatnonregistreduser" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnselcatnonregistreduser" name="btnselcatnonregistreduser" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- Selected Category Non-Registerd Users End -->


        <!-- Selected Category Registerd Users Start -->
       	<div class="fl w500 box">
        	<form name="form_selcatregistreduser" id="form_selcatregistreduser">
				<div class="header">Selected Category Registerd Users</div>
				<div class="cont">
                    <div>
                        Webservice Name: selcatregistreduser
                    </div>
                    <div style="font-weight: bold">Username</div>
					<div>
						<input type="text" id="selcatnonUsername" name="selcatnonUsername" />
					</div>
					<div style="font-weight: bold">Password</div>
					<div>
						<input type="text" id="selcatnonPassword" name="selcatnonPassword" />
					</div>
                    <div style="font-weight: bold">Category ID</div>
					<div>
						<input type="text" id="category_id" name="category_id" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_selcatregistreduser" name="request_selcatregistreduser" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_selcatregistreduser" name="response_selcatregistreduser" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnselcatregistreduser" name="btnselcatregistreduser" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- Selected Category Registerd Users End -->


        <!-- Detail View Start -->
       	<div class="fl w500 box">
        	<form name="form_detailview" id="form_detailview">
				<div class="header">Detail View</div>
				<div class="cont">
                    <div>
                        Webservice Name: detailview
                    </div>
                    <div style="font-weight: bold">Image ID</div>
					<div>
						<input type="text" id="image_id" name="image_id" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_detailview" name="request_detailview" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_detailview" name="response_detailview" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btndetailview" name="btndetailview" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- Selected Category Non-Registerd Users End -->

        <!-- videos Start -->
       	<div class="fl w500 box">
        	<form name="form_videos" id="form_videos">
				<div class="header">Videos</div>
				<div class="cont">
                    <div>
                        Webservice Name: videos
                    </div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_videos" name="request_videos" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_videos" name="response_videos" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnvideos" name="btnvideos" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- videos End -->

        <!-- caretips Start -->
       	<div class="fl w500 box">
        	<form name="form_caretips" id="form_caretips">
				<div class="header">Care Tips</div>
				<div class="cont">
                    <div>
                        Webservice Name: caretips
                    </div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_caretips" name="request_caretips" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_caretips" name="response_caretips" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btncaretips" name="btncaretips" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- videos End -->

        <!-- login Start -->
       	<div class="fl w500 box">
        	<form name="form_login" id="form_login">
				<div class="header">Login</div>
				<div class="cont">
                    <div>
                        Webservice Name: login
                    </div>
                    <div style="font-weight: bold">Username</div>
					<div>
						<input type="text" id="loginUsername" name="loginUsername" />
					</div>
					<div style="font-weight: bold">Password</div>
					<div>
						<input type="text" id="loginPassword" name="loginPassword" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_login" name="request_login" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_login" name="response_login" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnlogin" name="btnlogin" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- login End -->

        <!-- forgotpassword Start -->
       	<div class="fl w500 box">
        	<form name="form_forgotpassword" id="form_forgotpassword">
				<div class="header">Forgot Password</div>
				<div class="cont">
                    <div>
                        Webservice Name: forgotpassword
                    </div>
                    <div style="font-weight: bold">Username</div>
					<div>
						<input type="text" id="forgotpasswordUsername" name="forgotpasswordUsername" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_forgotpassword" name="request_forgotpassword" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_forgotpassword" name="response_forgotpassword" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnforgotpassword" name="btnforgotpassword" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- forgotpassword End -->

        <!-- registration Start -->
       	<div class="fl w500 box">
        	<form name="form_caretips" id="form_caretips">
				<div class="header">Registration</div>
				<div class="cont">
                    <div>
                        Webservice Name: registration
                    </div>
                    <div style="font-weight: bold">Firstname</div>
					<div>
						<input type="text" id="regFirstname" name="regFirstname" />
					</div>
                    <div style="font-weight: bold">Lastname</div>
					<div>
						<input type="text" id="regLastname" name="regLastname" />
					</div>
                    <div style="font-weight: bold">Email</div>
					<div>
						<input type="text" id="regEmail" name="regEmail" />
					</div>
                    <div style="font-weight: bold">Password</div>
					<div>
						<input type="text" id="regPassword" name="regPassword" />
					</div>
                    <div style="font-weight: bold">Request</div>
					<textarea class="reqT" id="request_registration" name="request_registration" rows="5" cols="58"></textarea>
					<div style="font-weight: bold">Response</div>
					<textarea class="resT" id="response_registration" name="response_registration" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btnregistration" name="btnregistration" value="Execute"/>
					</div>
				</div>
			</form>
		</div>
		<!-- registration End -->
</div>
</body>
</html> 