<?php

if (!is_file("../config.php")) 
  header("Location: ../install.php"); 


include_once "../config.php";

$type = optional_param('type', 'managecourses');

if (isset($_SESSION['userid'])) {
  $USER = get_record("user", array("id" => $_SESSION['userid']));
  
  if (!$data = get_record("roles", array("userid" => $USER->id, "roleid" => 1)))
    die("Only for admins.");
} else {
  $noredirect = false;
  if(isset($_COOKIE['mcm_username']) && isset($_COOKIE['mcm_password'])){
    if ($USER = get_record("user", array("username" => $_COOKIE['mcm_username'], "password" => $_COOKIE['mcm_password']))) {
      $_SESSION['userid'] = $USER->id;
      if (!$data = get_record("roles", array("userid" => $USER->id, "roleid" => 1)))
        die("Only for admins.");
    }
  }
  if (empty($USER->id)) {
    header("Location: login.php");
    die();
  }
}

unset($data);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Mobile Course Manager Admin area</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="../css/sort_table.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="../css/sort_table_theme.css" type="text/css" media="screen" title="default" />
<link rel="stylesheet" href="../css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="../css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="../js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.13.custom.min.js"></script> 
<script type="text/javascript" src="../js/jquery.dataTables.js"></script> 
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script> 
<script type="text/javascript" src="../js/jquery.listselect.min.js"></script> 
<script type="text/javascript" src="../js/bsn.AutoSuggest_c_2.0.js"></script>

<!--  checkbox styling script -->
<script src="../js/ui.core.js" type="text/javascript"></script>
<script src="../js/ui.checkbox.js" type="text/javascript"></script>
<script src="../js/jquery.bind.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('input').checkBox();
	$('#toggle-all').click(function(){
 	$('#toggle-all').toggleClass('toggle-checked');
	$('#mainform input[type=checkbox]').checkBox('toggle');
	return false;
	});
});
function openpopup(url,name,options,fullscreen) {
  fullurl = url;
  windowobj = window.open(fullurl,name,options);
  if (fullscreen) {
     windowobj.moveTo(0,0);
     windowobj.resizeTo(screen.availWidth,screen.availHeight);
  }
  windowobj.focus();
  return false;
}
//]]>
</script>  

<![if !IE 7]>

<!--  styled select box script version 1 -->
<script src="../js/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>
 

<![endif]>

<!--  styled select box script version 2 --> 
<script src="../js/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
	$('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script>

<!--  styled select box script version 3 --> 
<script src="../js/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script>

<!--  styled file upload script --> 
<script src="../js/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $("input.file_1").filestyle({ 
          image: "../images/forms/choose-file.gif",
          imageheight : 21,
          imagewidth : 78,
          width : 200
      });
  });
</script>

<!-- Custom jquery scripts -->
<script src="../js/custom_jquery.js" type="text/javascript"></script>
 
<!-- Tooltips -->
<script src="../js/jquery.tooltip.js" type="text/javascript"></script>
<script src="../js/jquery.dimensions.js" type="text/javascript"></script>
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
<link rel="stylesheet" href="../css/datePicker.css" type="text/css" />
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
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
<script src="../js/jquery.pngFix.pack.js" type="text/javascript"></script>
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
	<a href=""><img src="../images/shared/logo.png" width="156" height="40" alt="" /></a>
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
 
<!--  start nav-outer-repeat................................................................................................. START -->
<div class="nav-outer-repeat"> 
<!--  start nav-outer -->
<div class="nav-outer"> 


		<!-- start nav-right -->
		<div id="nav-right">
		
			<div class="nav-divider">&nbsp;</div>
			<div class="showhide-account"><img src="../images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
			<div class="nav-divider">&nbsp;</div>
			<a href="logout.php" id="logout"><img src="../images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
			<div class="clear">&nbsp;</div>
		
			<!--  start account-content -->	
			<div class="account-content">
			<div class="account-drop-inner">
				<a href="<?php echo $mcm_server; ?>/contacts.php?t=mcm" target="_blank" id="acc-settings">Support</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="<?php echo $mcm_server; ?>/userarea.php?t=mcm" target="_blank" id="acc-details">Personal details </a>
			</div>
			</div>
			<!--  end account-content -->
		
		</div>
		<!-- end nav-right -->
		
		<!--  start nav -->
		<div class="nav">
		<div class="table">
		
<?php
$c=0;
while (list($menulevel1name,$menulevel1) = each($admin_menu)) {
  $c++;
  
  switch ($c) {
      case 1:
          $left = 0;
          break;
      case 2:
          $left = 100;
          break;
      case 3:
          $left = 180;
          break;
      case 4:
          $left = 320;
          break;
  }
  
  //if (array_key_exists($type, $admin_menu[$menulevel1name])) { $topstyleindex = 'current'; $topstyle = 'show'; } else { $topstyleindex = 'select'; $topstyle = ''; }
  $topstyleindex = 'select';
  $topstyle = ''; 
  ?>
		<ul class="<?php echo $topstyleindex;?>"><li><a href="#nogo"><b><?php echo $menulevel1name; ?></b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub <?php echo $topstyle; ?>" style="left:<?php echo $left;?>px">
			<ul class="sub">
  <?php
  while (list($menulevel2name,$menulevel2) = each($menulevel1)) {
    if ($type == $menulevel2name) $substyle = 'class="sub_show"'; else $substyle = '';
    echo '<li '.$substyle.'><a href="?type='.$menulevel2name.'">'.$menulevel2.'</a></li>';
  }
  ?>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
  <?php
}
?>
		
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
		</div>
		<!--  start nav -->

</div>
<div class="clear"></div>
<!--  start nav-outer -->
</div>
<!--  start nav-outer-repeat................................................... END -->

  <div class="clear"></div>
 
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
<?php

if ((float)$options['_currentversion'] < (float)$options['_latestversion']) {
  echo '<div style="text-align:right;">You use version: '.$options['_currentversion'].', latest version: '.$options['_latestversion'].' <a href="'.$mcm_server.'/updating/index.php?type=mcm" target="_blank">(update)</a></div>';
}


if (!empty($type)) {
  include_once $CFG['dirroot']."/admin/type/{$type}.php";
}

?>

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
	Admin Skin &copy; Copyright Internet Dreams Ltd. Powered by <a href="http://netcourse.org/" target="_blank">NetCourse.org</a></div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
 
</body>
</html>




