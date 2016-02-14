<?php

if (!is_file("config.php")) 
  header("Location: install.php"); 

include_once "config.php";

if (isset($_SESSION['userid'])) 
  $USER = get_record("user", array("id" => $_SESSION['userid']));
else 
  if(isset($_COOKIE['mcm_username']) && isset($_COOKIE['mcm_password']))
    if ($USER = get_record("user", array("username" => $_COOKIE['mcm_username'], "password" => $_COOKIE['mcm_password']))) 
      $_SESSION['userid'] = $USER->id;


if (empty($_SESSION['userid'])) 
  $pagetitle = "Please login";
else
  $pagetitle = "Login details";
  
  


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $pagetitle; ?></title>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<style type="text/css" media="screen">@import "<?php echo $CFG['wwwroot']; ?>/webiphone/iphonenav.css";</style>
<script type="application/x-javascript" src="<?php echo $CFG['wwwroot']; ?>/webiphone/iphonenav.js"></script>
<script type="application/x-javascript" src="<?php echo $CFG['wwwroot']; ?>/js/jquery-1.5.1.min.js"></script>
<script>
$(document).ready(function(){
  $('#optionsave').click(function() {
    $.post('save.php', { login: $('#optionlogin').val(), pass: $('#optionpassword').val() }, function(data) {
      if (data == "error") {
        alert("Incorrect login or password. Please try again.");
      } else {
        window.location = "<?php echo $CFG['wwwroot']; ?>";
      }
    });

  });
  
  $('#optionsaveasguest').click(function() {
    $.post('save.php', { login: "guest", pass: "guest" }, function(data) {
        window.location = "<?php echo $CFG['wwwroot']; ?>";
    });
  });

  $('#optionlogout').click(function() {
    $.post('logout.php', function(data) {
        window.location = "<?php echo $CFG['wwwroot']; ?>";
    });
  });
  
});
</script>
</head>

<body>
    <h1 id="pageTitle"></h1>
    <a id="homeButton" class="button" href="index.php" target="_webapp">Courses</a>

	<div id="options" class="panel" title="Account" selected="true">
	<?php
	if (empty($_SESSION['userid'])) {
	?>
	  <ul>
		<li>Login <input type="text" name="login" id="optionlogin" value="" autocorrect="off" autocapitalize="off" /></li>
		<li>Password <input type="password" name="password" id="optionpassword" value="" style="width:170px;" autocorrect="off" autocapitalize="off" /></li>
		<li><a class="whiteButton" type="button" href="#home" id="optionsave" style="margin:0px;">Login</a></li>
		<?php
		if($options['guest_enable'] == 1) {
		?>
		<li><a class="whiteButton" type="button" href="#home" id="optionsaveasguest" style="margin:0px;">Login as guest</a></li>
		<?php
		}
		?>
	  </ul>
	<?php } else { ?>
	  <ul>
		<li>Login <input type="text" name="login" id="optionlogin" value="<?php echo $USER->username; ?>" autocorrect="off" autocapitalize="off" /></li>
		<li><a class="whiteButton" type="button" href="#home" id="optionlogout" style="margin:0px;">Logout</a></li>
	  </ul>
	<?php } ?>
	</div>
</body>
</html>
