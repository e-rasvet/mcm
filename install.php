<?php


if ($_GET['step'] == 2) {
  session_start();
  
  $_SESSION['accountemail'] = $_POST['accountemail'];
  
  include_once "inc/ez_sql_core.php";
  include_once "inc/ez_sql_mysql.php";
  
  $link = mysql_connect($_POST['dbhost'], $_POST['dbusername'], $_POST['dbpassword']);
  if (!$link) 
    $error = "Could not connect to DataBase: ". mysql_error();
  else {
    $db_selected = mysql_select_db($_POST['dbname'], $link);
  
    if (!$db_selected) 
      $error = "Could not select DataBase ".$_POST['dbname'].": ". mysql_error();
  }
  
  
  if (!empty($error)) unset($_GET['step']);
  
  if (empty($error)) {
    $data = array();
    $data['host']    = $_POST['dbhost'];
    $data['dbname']  = $_POST['dbname'];
    $data['dbuser']  = $_POST['dbusername'];
    $data['dbpass']  = $_POST['dbpassword'];
    $data['prefix']  = $_POST['dbprefix'];
    $data['wwwroot'] = $_POST['wwwroot'];
    $data['dirroot'] = $_POST['dirroot'];
    
$configfile = '<?php

error_reporting(0); 

$CFG = array();
$CFG[\'host\']        = "{host}";
$CFG[\'dbname\']      = "{dbname}";
$CFG[\'dbuser\']      = "{dbuser}";
$CFG[\'dbpass\']      = "{dbpass}";

$CFG[\'prefix\']      = "{prefix}";

$CFG[\'wwwroot\']     = "{wwwroot}";


$CFG[\'dirroot\']     = "{dirroot}";

session_start();

include_once $CFG[\'dirroot\']."/inc/ez_sql_core.php";
include_once $CFG[\'dirroot\']."/inc/ez_sql_mysql.php";
include_once $CFG[\'dirroot\']."/inc/f.php";

$optionsarray = get_records("options");
$options = array();
while(list($key,$value)=each($optionsarray)) {
  $options[$value->name] = $value->value;
}
';
    while (list($key,$value)=each($data)){
      $configfile = str_replace("{".$key."}", $value, $configfile);
    }
    
    $fp = fopen("config.php", "w+");
    fwrite($fp, $configfile);
    fclose($fp);
    
    if (!is_file("config.php")) {
      $needconfigfile = true;
    }
    
    //--------------------Install SQL--------------------//
    
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."apps_choice` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `text` text NOT NULL,   `category` varchar(255) NOT NULL,   `image` int(11) DEFAULT '0',   `soundtext` int(11) DEFAULT '0',   `var1` varchar(255) DEFAULT NULL,   `var2` varchar(255) DEFAULT NULL,   `var3` varchar(255) DEFAULT NULL,   `var4` varchar(255) DEFAULT NULL,   `var5` varchar(255) DEFAULT NULL,   `var6` varchar(255) DEFAULT NULL,   `var7` varchar(255) DEFAULT NULL,   `var8` varchar(255) DEFAULT NULL,   `var9` varchar(255) DEFAULT NULL,   `var10` varchar(255) DEFAULT NULL,   `var11` varchar(255) DEFAULT NULL,   `var12` varchar(255) DEFAULT NULL,   `cor1` int(2) DEFAULT '0',   `cor2` int(2) DEFAULT '0',   `cor3` int(2) DEFAULT '0',   `cor4` int(2) DEFAULT '0',   `cor5` int(2) DEFAULT '0',   `cor6` int(2) DEFAULT '0',   `cor7` int(2) DEFAULT '0',   `cor8` int(2) DEFAULT '0',   `cor9` int(2) DEFAULT '0',   `cor10` int(2) DEFAULT '0',   `cor11` int(2) DEFAULT '0',   `cor12` int(2) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `soundtext` (`soundtext`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."apps_listening` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `category` varchar(255) NOT NULL,   `feedback` text NOT NULL,   `soundtext` int(11) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."apps_quizzes` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `word` varchar(255) NOT NULL,   `translate` varchar(255) NOT NULL,   `var1` varchar(255) DEFAULT NULL,   `var2` varchar(255) DEFAULT NULL,   `var3` varchar(255) DEFAULT NULL,   `var4` varchar(255) DEFAULT NULL,   `var5` varchar(255) DEFAULT NULL,   `var6` varchar(255) DEFAULT NULL,   `var7` varchar(255) DEFAULT NULL,   `var8` varchar(255) DEFAULT NULL,   `soundword` int(11) DEFAULT '0',   `category` varchar(255) NOT NULL,   PRIMARY KEY (`id`),   KEY `soundword` (`soundword`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."apps_reading` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `title` varchar(255) NOT NULL,   `soundtext` int(11) DEFAULT '0',   `video` int(11) DEFAULT '0',   `category` varchar(255) NOT NULL,   PRIMARY KEY (`id`),   KEY `soundtext` (`soundtext`),   KEY `video` (`video`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."apps_reading_texts` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `readerid` int(11) NOT NULL,   `text` text NOT NULL,   `timing` varchar(10) DEFAULT NULL,   `image` int(11) DEFAULT '0',   `vocabulary` varchar(255) DEFAULT NULL,   `translation` varchar(255) DEFAULT NULL,   PRIMARY KEY (`id`),   KEY `image` (`image`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."apps_vocabulary` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `word` varchar(255) NOT NULL,   `translate` varchar(255) NOT NULL,   `text` text NOT NULL,   `image` int(11) DEFAULT '0',   `soundword` int(11) DEFAULT '0',   `soundtext` int(11) DEFAULT '0',   `video` int(11) DEFAULT '0',   `category` varchar(20) NOT NULL,   PRIMARY KEY (`id`),   KEY `word` (`word`),   KEY `image` (`image`),   KEY `soundword` (`soundword`),   KEY `soundtext` (`soundtext`),   KEY `video` (`video`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."course` (   `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,   `category` bigint(10) unsigned NOT NULL DEFAULT '0',   `password` varchar(50) NOT NULL DEFAULT '',   `fullname` varchar(254) NOT NULL DEFAULT '',   `shortname` varchar(100) NOT NULL DEFAULT '',   `summary` text,   `format` varchar(10) NOT NULL DEFAULT 'topics',   `showgrades` tinyint(2) unsigned NOT NULL DEFAULT '1',   `modinfo` longtext,   `guest` tinyint(2) unsigned NOT NULL DEFAULT '0',   `startdate` bigint(10) unsigned NOT NULL DEFAULT '0',   `enrolperiod` bigint(10) unsigned NOT NULL DEFAULT '0',   `numsections` mediumint(5) unsigned NOT NULL DEFAULT '1',   `visible` tinyint(1) unsigned NOT NULL DEFAULT '1',   `lang` varchar(30) NOT NULL DEFAULT '',   `timecreated` bigint(10) unsigned NOT NULL DEFAULT '0',   `timemodified` bigint(10) unsigned NOT NULL DEFAULT '0',   `enrollable` tinyint(1) unsigned NOT NULL DEFAULT '1',   `enrolstartdate` bigint(10) unsigned NOT NULL DEFAULT '0',   `enrolenddate` bigint(10) unsigned NOT NULL DEFAULT '0',   PRIMARY KEY (`id`),   KEY `mdl_cour_cat_ix` (`category`),   KEY `mdl_cour_sho_ix` (`shortname`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."course_student` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `userid` int(11) DEFAULT '0',   `courseid` int(11) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `courseid` (`courseid`),   KEY `userid` (`userid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."course_topic` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `title` varchar(255) NOT NULL,   `courseid` int(11) DEFAULT '0',   `data` text NOT NULL,   `order` int(11) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `courseid` (`courseid`),   KEY `order` (`order`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."log` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `userid` int(11) DEFAULT '0',   `apps` int(11) DEFAULT '0',   `courseid` int(11) DEFAULT '0',   `time` int(11) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `userid` (`userid`),   KEY `apps` (`apps`),   KEY `courseid` (`courseid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."options` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `name` varchar(255) NOT NULL,   `value` text NOT NULL,   PRIMARY KEY (`id`), UNIQUE (`name`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."roles` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `userid` int(11) DEFAULT '0',   `courseid` int(11) DEFAULT '0',   `roleid` int(11) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `userid` (`userid`),   KEY `courseid` (`courseid`),   KEY `roleid` (`roleid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."score` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `userid` int(11) DEFAULT '0',   `apps` int(11) DEFAULT '0',   `courseid` int(11) DEFAULT '0',   `appsid` int(11) NOT NULL,   `answer` varchar(255) DEFAULT '0',   `score` int(11) DEFAULT '0',   `correct` int(11) DEFAULT '0',   `time` int(11) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `userid` (`userid`),   KEY `apps` (`apps`),   KEY `appsid` (`appsid`),   KEY `score` (`score`),   KEY `correct` (`correct`),   KEY `time` (`time`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);
mysql_query("CREATE TABLE IF NOT EXISTS `".$data['prefix']."user` (   `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,   `auth` varchar(20) NOT NULL DEFAULT 'manual',   `confirmed` tinyint(1) NOT NULL DEFAULT '1',   `deleted` tinyint(1) NOT NULL DEFAULT '0',   `username` varchar(100) NOT NULL DEFAULT '',   `password` varchar(32) NOT NULL DEFAULT '',   `firstname` varchar(100) NOT NULL DEFAULT '',   `lastname` varchar(100) NOT NULL DEFAULT '',   `email` varchar(100) NOT NULL DEFAULT '',   `emailstop` tinyint(1) unsigned NOT NULL DEFAULT '0',   `icq` varchar(15) NOT NULL DEFAULT '',   `skype` varchar(50) NOT NULL DEFAULT '',   `yahoo` varchar(50) NOT NULL DEFAULT '',   `aim` varchar(50) NOT NULL DEFAULT '',   `msn` varchar(50) NOT NULL DEFAULT '',   `phone1` varchar(20) NOT NULL DEFAULT '',   `phone2` varchar(20) NOT NULL DEFAULT '',   `institution` varchar(40) NOT NULL DEFAULT '',   `department` varchar(30) NOT NULL DEFAULT '',   `address` varchar(70) NOT NULL DEFAULT '',   `city` varchar(20) NOT NULL DEFAULT '',   `country` varchar(2) NOT NULL DEFAULT '',   `lang` varchar(30) NOT NULL DEFAULT 'en',   `timezone` varchar(100) NOT NULL DEFAULT '99',   `firstaccess` bigint(10) unsigned NOT NULL DEFAULT '0',   `lastaccess` bigint(10) unsigned NOT NULL DEFAULT '0',   `lastlogin` bigint(10) unsigned NOT NULL DEFAULT '0',   `currentlogin` bigint(10) unsigned NOT NULL DEFAULT '0',   `lastip` varchar(15) NOT NULL DEFAULT '',   `secret` varchar(15) NOT NULL DEFAULT '',   `picture` tinyint(1) NOT NULL DEFAULT '0',   `url` varchar(255) NOT NULL DEFAULT '',   `description` text,   PRIMARY KEY (`id`),   UNIQUE KEY `mdl_user_mneuse_uix` (`username`),   KEY `mdl_user_del_ix` (`deleted`),   KEY `mdl_user_con_ix` (`confirmed`),   KEY `mdl_user_fir_ix` (`firstname`),   KEY `mdl_user_las_ix` (`lastname`),   KEY `mdl_user_cit_ix` (`city`),   KEY `mdl_user_cou_ix` (`country`),   KEY `mdl_user_las2_ix` (`lastaccess`),   KEY `mdl_user_ema_ix` (`email`),   KEY `mdl_user_aut_ix` (`auth`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;", $link);


  }
}

if ($_GET['step'] == 3) {
  include_once "config.php";

  $datam              = array();
  $datam['username']  = $_POST['username'];
  $datam['password']  = md5($_POST['password']);
  $datam['firstname'] = $_POST['firstname'];
  $datam['lastname']  = $_POST['lastname'];
  $datam['email']     = $_POST['email'];
  
  $id = insert_record("user", $datam);
  
  if (!get_record("roles", array("userid" => $id, "courseid" => 0, "roleid" => 1))) {
    $datam              = array();
    $datam['userid']    = $id;
    $datam['courseid']  = 0;
    $datam['roleid']    = 1;
  
    $id = insert_record("roles", $datam);
  }
  
  //------ADD START WARIABLES-------------------//
  
  $postdata = http_build_query(array('email' => $_SESSION['accountemail'], 'siteurl' => $CFG['wwwroot']));
  $opts = array('http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
       ));
  $context  = stream_context_create($opts);
  $result = @file_get_contents($mcm_server.'/newscriptinstalled.php', false, $context);
  
  
  $datam              = array();
  $datam['name']      = 'serverlogin';
  $datam['value']     = $_SESSION['accountemail'];
  insert_record("options", $datam);
  
  $datam              = array();
  $datam['name']      = '_currentversion';
  $datam['value']     = '1.0';
  insert_record("options", $datam);
  
  $datam              = array();
  $datam['name']      = '_latestversion';
  $datam['value']     = '1.0';
  insert_record("options", $datam);
  
  $datam               = array();
  $datam['username']   = 'guest';
  $datam['password']   = md5('guest');
  $datam['firstname']  = 'guest';
  $datam['lastname']   = 'guest';
  $datam['email']      = 'guest';
  insert_record("user", $datam);
  
  //------------Set writable permission---------------//
  
  chmod_R($CFG['dirroot']."/datas", 0777);

}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Mobile Course Manager Installation</title>
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="css/sort_table.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="css/sort_table_theme.css" type="text/css" media="screen" title="default" />
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script> 
<script type="text/javascript" src="js/jquery.dataTables.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script> 
<script type="text/javascript" src="js/jquery.listselect.min.js"></script> 

<!--  checkbox styling script -->
<script src="js/ui.core.js" type="text/javascript"></script>
<script src="js/ui.checkbox.js" type="text/javascript"></script>
<script src="js/jquery.bind.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$('input').checkBox();
	$('#toggle-all').click(function(){
 	$('#toggle-all').toggleClass('toggle-checked');
	$('#mainform input[type=checkbox]').checkBox('toggle');
	return false;
	});
});
</script>  

<![if !IE 7]>

<!--  styled select box script version 1 -->
<script src="js/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>
 

<![endif]>

<!--  styled select box script version 2 --> 
<script src="js/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
	$('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script>

<!--  styled select box script version 3 --> 
<script src="js/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script>

<!--  styled file upload script --> 
<script src="js/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $("input.file_1").filestyle({ 
          image: "images/forms/choose-file.gif",
          imageheight : 21,
          imagewidth : 78,
          width : 200
      });
  });
</script>

<!-- Custom jquery scripts -->
<script src="js/custom_jquery.js" type="text/javascript"></script>
 
<!-- Tooltips -->
<script src="js/jquery.tooltip.js" type="text/javascript"></script>
<script src="js/jquery.dimensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$('a.info-tooltip ').tooltip({
		track: true,
		delay: 0,
		fixPNG: true, 
		showURL: false,
		showBody: " - ",
		top: -35,
		left: 5
	});
});
</script> 


<!--  date picker script -->
<link rel="stylesheet" href="css/datePicker.css" type="text/css" />
<script src="js/date.js" type="text/javascript"></script>
<script src="js/jquery.datePicker.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
        $(function()
{

// initialise the "Select date" link
$('#date-pick')
	.datePicker(
		// associate the link with a date picker
		{
			createButton:false,
			startDate:'01/01/2005',
			endDate:'31/12/2020'
		}
	).bind(
		// when the link is clicked display the date picker
		'click',
		function()
		{
			updateSelects($(this).dpGetSelected()[0]);
			$(this).dpDisplay();
			return false;
		}
	).bind(
		// when a date is selected update the SELECTs
		'dateSelected',
		function(e, selectedDate, $td, state)
		{
			updateSelects(selectedDate);
		}
	).bind(
		'dpClosed',
		function(e, selected)
		{
			updateSelects(selected[0]);
		}
	);
	
var updateSelects = function (selectedDate)
{
	var selectedDate = new Date(selectedDate);
	$('#d option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
	$('#m option[value=' + (selectedDate.getMonth()+1) + ']').attr('selected', 'selected');
	$('#y option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
}
// listen for when the selects are changed and update the picker
$('#d, #m, #y')
	.bind(
		'change',
		function()
		{
			var d = new Date(
						$('#y').val(),
						$('#m').val()-1,
						$('#d').val()
					);
			$('#date-pick').dpSetSelected(d.asString());
		}
	);

// default the position of the selects to today
var today = new Date();
updateSelects(today.getTime());

// and update the datePicker to reflect it...
$('#d').trigger('change');
});
</script>

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="js/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body> 
<!-- Start: page-top-outer -->
<div id="page-top-outer">    

<!-- Start: page-top -->
<div id="page-top">

	<!-- start logo -->
	<div id="logo">
	<a href=""><img src="images/shared/logo.png" width="156" height="40" alt="" /></a>
	</div>
	<!-- end logo -->
	
	<!--  start top-search -->
	<div id="top-search">

	</div>
 	<!--  end top-search -->
 	<div class="clear"></div>

</div>
<!-- End: page-top -->

</div>
<!-- End: page-top-outer -->
	
<div class="clear">&nbsp;</div>
 

<div id="content-outer">
<!-- start content -->
<div id="content">



	<!--  start page-heading -->
	<div id="page-heading">
		<h1>Installtion</h1>
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
		
		<!-- INSTALLATION CODE HERE -->
<?php

    if ($_GET['step'] == 3) {
  
   
    //---------------STEP 3--------------------------//
    ?>
		<!--  start step-holder -->
		<div id="step-holder">
			<div class="step-no-off">1</div>
			<div class="step-light-left"><a href="#">Add server details</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no-off">2</div>
			<div class="step-light-left">Admin account details</div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no">3</div>
			<div class="step-dark-left">Finish</div>
			<div class="step-dark-round">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		
		<h2>Installation complete.</h2> <br /><br />
		
		<?php
		if (!is_writable($CFG['dirroot']."/datas")) {
		  echo '<p>Please make "datas" directory (and all sub directories) wtitable. If you have ssh access to your server please do this command:</p>';
		  echo '<p>chmod -R 0777 '.$CFG['dirroot'].'/datas</p><br />';
		}
		?>
		
		<p>Please delete install.php file from root script derectory.</p><br />
		
		<p>Now you can create courses and content (or download it from our server).</p><br />
		
		<a href="admin/index.php">Admin area</a>
		
	<?php
  
  } else if ($_GET['step'] == 2) {
  
    if ($needconfigfile) {
      echo '<span style="padding:20px;font-size:15px;">Could not create config.php file, Please add it to root of the script manualy.</span>';
      ?>
      <div style="padding:8px;margin:8px;background-color:#eee;border:1px solid #aaa;padding-bottom:25px;"><?php echo str_replace("\n", "\n<br />", str_replace("<", "&gt;", str_replace("<", "&lt;", $configfile))); ?></div>
      <?php
    }
    
    //---------------STEP 2--------------------------//
    ?>
<script type="text/javascript" charset="utf-8">

function validate_form(form) { 
    for (var i=0; i < form.elements.length; i++) {
      if ($(form.elements[i]).attr("class") == "inp-form-error") {
        if (!$(form.elements[i]).val()) {
          alert('Please fill "'+$(form.elements[i]).attr("name")+'" the field.'); 
          return false;
        }
      }
    }
    return true;
}

</script> 
		<!--  start step-holder -->
		<div id="step-holder">
			<div class="step-no-off">1</div>
			<div class="step-light-left"><a href="#">Add server details</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no">2</div>
			<div class="step-dark-left">Admin account details</div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off">3</div>
			<div class="step-light-left">Finish</div>
			<div class="step-light-round">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<span style="padding:20px;font-size:15px;"><?php if(isset($error)) echo $error; ?></span>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" method="post" action="install.php?step=3" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Username:</td>
			<td><input type="text" name="username" style="width:300px;padding:4px;" value="" /></td>
			<td>
			<div class="bubble-left"></div>
			<div class="bubble-inner">Admin user details</div>
			<div class="bubble-right"></div>
			</td>
		</tr>
		<tr>
			<th valign="top">Password:</td>
			<td><input type="text" name="password" style="width:300px;padding:4px;" value="" /></td>
		</tr>
		<tr>
			<th valign="top">Firstname:</td>
			<td><input type="text" name="firstname" style="width:300px;padding:4px;" value="" /></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Lastname:</td>
			<td><input type="text" name="lastname" style="width:300px;padding:4px;" value="" /></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Email:</td>
			<td><input type="text" name="email" style="width:300px;padding:4px;" value="<?php if (!empty($_POST['accountemail'])) echo $_POST['accountemail']; ?>" /></td>
			<td></td>
		</tr>
	<tr>
		<th>&nbsp;</td>
		<td valign="top">
			<input type="submit" value="" class="form-submit" />
			<input type="reset" value="" class="form-reset"  />
		</td>
		<td></td>
	</tr>
	</table>
	
	</form>
	<!-- end id-form  -->
	<?php
  
  } else {
  
if (is_file("config.php")) {
  echo '<span style="padding:20px;font-size:15px;">You already installted the script, please delete install.php file.</span>';
} else {
  ?>
  <style>
 #id-form td.firstrow {
    font-weight: bold;
    line-height: 28px;
    min-width: 230px;
    padding: 0 0 10px;
    text-align: left;
    width: 230px;
  }
.inp-form-error {
    width: 287px;
}
  </style>
  
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
	
	
		<!--  start step-holder -->
		<div id="step-holder">
			<div class="step-no">1</div>
			<div class="step-dark-left"><a href="#">Add server details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off">2</div>
			<div class="step-light-left">Admin account details</div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no-off">3</div>
			<div class="step-light-left">Finish</div>
			<div class="step-light-round">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<span style="padding:20px;font-size:15px;"><?php if(isset($error)) echo $error; ?></span>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" method="post" action="install.php?step=2">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<td class="firstrow" valign="top">Database host:</td>
			<td><input type="text" name="dbhost"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['dbhost'])) echo $_POST['dbhost']; else echo 'localhost'; ?>" /></td>
			<td>
			<div class="bubble-left"></div>
			<div class="bubble-inner">Your mysql details</div>
			<div class="bubble-right"></div>
			</td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Database Username:</td>
			<td><input type="text" name="dbusername"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['dbusername'])) echo $_POST['dbusername']; ?>" /></td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Database user Password:</td>
			<td><input type="text" name="dbpassword"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['dbpassword'])) echo $_POST['dbpassword']; ?>" /></td>
			<td></td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Database name:</td>
			<td><input type="text" name="dbname"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['dbname'])) echo $_POST['dbname']; ?>" /></td>
			<td></td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Table prefix:</td>
			<td><input type="text" name="dbprefix"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['dbprefix'])) echo $_POST['dbprefix']; else echo 'mcm_'; ?>" /></td>
			<td></td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Site URL:</td>
			<td><input type="text" name="wwwroot"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['wwwroot'])) echo $_POST['wwwroot']; else echo str_replace("/install.php", "", "http://".$_SERVER['HTTP_HOST']."".$_SERVER['SCRIPT_NAME']); ?>" /></td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Site root:</td>
			<td><input type="text" name="dirroot"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['dirroot'])) echo $_POST['dirroot']; else echo  dirname(__FILE__); ?>" /></td>
		</tr>
		<tr>
			<td class="firstrow" valign="top">Email:</td>
			<td><input type="text" name="accountemail"  style="width:300px;padding:4px;" value="<?php if (!empty($_POST['accountemail'])) echo $_POST['accountemail']; ?>" /></td>
			<td>
			<div class="bubble-left"></div>
			<div class="bubble-inner">Your email for trial licence</div>
			<div class="bubble-right"></div>
			</td>
		</tr>
	<tr>
		<td class="firstrow">&nbsp;</td>
		<td valign="top">
			<input type="submit" value="" class="form-submit" />
			<input type="reset" value="" class="form-reset"  />
		</td>
		<td></td>
	</tr>
	</table>
	
	</form>
	<!-- end id-form  -->

	</td>
	<td>
    </td>
</tr>
<tr>
<td><img src="images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
<td></td>
</tr>
</table>
 
<div class="clear"></div>
  
  <?php
    }
}


?>
		<!-- INSTALLATION CODE HERE ------------END-->

 
<div class="clear"></div>
		 
		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	


	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->

<div class="clear">&nbsp;</div>
    
<!-- start footer -->         
<div id="footer">
<!-- <div id="footer-pad">&nbsp;</div> -->
	<!--  start footer-left -->
	<div id="footer-left">
	Admin Skin &copy; Copyright Internet Dreams Ltd. <a href="">www.netdreams.co.uk</a>. All rights reserved.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
 
</body>
</html>
<?php


function chmod_R($path, $filemode) {
    if (!is_dir($path))
        return chmod($path, $filemode);

    $dh = opendir($path);
    while (($file = readdir($dh)) !== false) {
        if($file != '.' && $file != '..') {
            $fullpath = $path.'/'.$file;
            if(is_link($fullpath))
                return FALSE;
            elseif(!is_dir($fullpath))
                if (!chmod($fullpath, $filemode))
                    return FALSE;
            elseif(!chmod_R($fullpath, $filemode))
                return FALSE;
        }
    }

    closedir($dh);

    if(chmod($path, $filemode))
        return TRUE;
    else
        return FALSE;
} 
