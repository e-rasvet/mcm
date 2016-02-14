<?php

include_once "../config.php";

$email           = optional_param('email', NULL, PARAM_TEXT);
$username        = optional_param('username', NULL, PARAM_TEXT);
$password        = optional_param('password', NULL, PARAM_TEXT);
$remember        = optional_param('remember', NULL, PARAM_TEXT);
$id              = optional_param('id', NULL, PARAM_INT);
$p               = optional_param('p', NULL, PARAM_TEXT);


if (isset($id) && isset($p)) {
  if ($user = get_record("user", array("id" => $id))) {
    if ($user->description == $p) {
      $datam                 = array();
      $datam['password']     = md5($p);
      $datam['id']           = $user->id;
    
      $id = insert_record("user", $datam);
      
      echo 'Your new password is: '.$p.'';
      
      die();
    }
  }
}

if ($email) {
  if ($user = get_record("user", array("email" => $email))) {
    $datam                 = array();
    $datam['description']  = createRandomPassword();
    $datam['id']           = $user->id;
    
    $id = insert_record("user", $datam);
    
    $subject = "Password Restore";
    $message = "Hello!\n\nIf you need new password, please open this link in your browser: ".$CFG['wwwroot']."/admin/login.php?id=".$id."&p=".$datam['description']."\n\n";
    
	$to      = $email;
	//$headers = 'From: support@netcourse.org' . "\r\n" .
	//	'Reply-To: support@netcourse.org';
	
	mail($to, $subject, $message);
	
    echo "Please check your email now";
    die();
  } else {
    echo "Incorrect email!";
    die();
  }
}

if ($username) {
  if ($aunt = get_record("user", array("username" => $username, "password" => md5($password)))) {
    $_SESSION['userid'] = $aunt->id;
    
    if ($remember == "on") {
      $urlinfo = parse_url($CFG['wwwroot']);
      setcookie("mcm_username", $username, time()+3600*24*7, "/", $urlinfo['host']);
      setcookie("mcm_password", md5($password), time()+3600*24*7, "/", $urlinfo['host']);
    }
    
    header("Location: index.php");
    die();
  } else {
    $errorreport = "Incorrect username or password";
  }
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Mobile Course Manager Admin area login</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<script src="../js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="../js/custom_jquery.js" type="text/javascript"></script>
<script src="../js/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body id="login-bg"> 
 
<div id="login-holder">

	<!-- start logo -->
	<div id="logo-login">
		<a href="index.html"><img src="../images/shared/logo.png" width="156" height="40" alt="" /></a>
	</div>
	<!-- end logo -->
	
	<div class="clear"></div>
	
	<!--  start loginbox ................................................................................. -->
	<div id="loginbox">

	<?php if (isset($errorreport)) echo '<div id="forgotbox-text">'.$errorreport.'</div>'; ?>
	
	<!--  start login-inner -->
	<div id="login-inner">
	<form id="formadd" name="formadd_point" method="post" action="login.php">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Username</th>
			<td><input type="text" name="username" value=""  class="login-inp" /></td>
		</tr>
		<tr>
			<th>Password</th>
			<td><input type="password"  name="password" value="" class="login-inp" /></td>
		</tr>
		<tr>
			<th></th>
			<td valign="top"><input type="checkbox" class="checkbox-size" id="login-check" name="remember" /><label for="login-check">Remember me</label></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" class="submit-login"  /></td>
		</tr>
		</table>
	</form>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
	<a href="#" class="forgot-pwd">Forgot Password?</a>
 </div>
 <!--  end loginbox -->
 
	<!--  start forgotbox ................................................................................... -->
	<div id="forgotbox">
		<div id="forgotbox-text">Please send us your email and we'll reset your password.</div>
		<!--  start forgot-inner -->
		<div id="forgot-inner">
		<form id="formadd" name="formadd_point" method="post" action="login.php">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Email address:</th>
			<td><input type="text" name="email" value=""   class="login-inp" /></td>
		</tr>
		<tr>
			<th> </th>
			<td><input type="submit" class="submit-login"  /></td>
		</tr>
		</table>
		</form>
		</div>
		<!--  end forgot-inner -->
		<div class="clear"></div>
		<a href="" class="back-login">Back to login</a>
	</div>
	<!--  end forgotbox -->

</div>
<!-- End: login-holder -->
</body>
</html>