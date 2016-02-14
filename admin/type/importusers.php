<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$id         = optional_param('id', NULL, PARAM_INT);


if ($_FILES['csvfile']['tmp_name']) {
  $csv = file_get_contents($_FILES['csvfile']['tmp_name']);
  $csvarray = explode("\n", $csv);
  $status = "";
  while(list($key,$value)=each($csvarray)) {
    if ($key == 0) {
      $fields = explode(",", $value);
      $fieldsm = array();
      while(list($f1,$f2)=each($fields)) {
        $fieldsm[trim($f2)] = $f1;
      }
    } else if (!empty($value)) {
      $userdata           = explode(",", $value);
      $datam              = array();
      $datam['username']  = trim($userdata[$fieldsm['username']]);
      $datam['password']  = md5(trim($userdata[$fieldsm['password']]));
      $datam['firstname'] = trim($userdata[$fieldsm['firstname']]);
      $datam['lastname']  = trim($userdata[$fieldsm['lastname']]);
      $datam['email']     = trim($userdata[$fieldsm['email']]);

      if (get_record("user", array("username" => $datam['username'])) && !$id) 
        $status .= statusmessage($datam['username'].' - Username exist try other one','red');
      else if (get_record("user", array("email" => $datam['email'])) && !$id) 
        $status .= statusmessage($datam['email'].' - Email exist try other one','red');
      else {
        $id = insert_record("user", $datam);
      }
    }
  }
  $status .= statusmessage('Users list was submited successfully');
}

?>



	<!--  start page-heading -->
	<div id="page-heading">
		<h1>Import users</h1>
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="<?php echo $CFG['wwwroot']; ?>/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="<?php echo $CFG['wwwroot']; ?>/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
	
	
		<!--  start step-holder -->
		<div id="step-holder">
			<div class="step-no">1</div>
			<div class="step-dark-left"><a href="#">Submit CSV data file</a></div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=importusers">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th>CSV file:</th>
			<td width="300px;"><input type="file" name="csvfile" class="file_1" /></td>
			<td>
			<div class="bubble-left"></div>
			<div class="bubble-inner">Please use UTF-8 encoding</div>
			<div class="bubble-right"></div>
			</td>
		
		</tr>
	<tr>
		<th>&nbsp;</th>
		<td valign="top">
			<input type="submit" value="" class="form-submit" />
			<input type="reset" value="" class="form-reset"  />
		</td>
		<td></td>
	</tr>
	</table>
	
	<?php if ($data->id) echo "<input type=\"hidden\" name=\"id\" value=\"{$data->id}\" />"; ?>
	</form>
	<!-- end id-form  -->
	
	<div style="padding-top:30px;">
<p>Here is an example of a valid import file (<a href="<?php echo $CFG['wwwroot'] ?>/admin/userslist.csv">download</a>):</p><br />

<p>username, password, firstname, lastname, email</p>
<p>jonest, verysecret, Tom, Jones, jonest@someplace.edu</p>
<p>reznort, somesecret, Trent, Reznor, reznort@someplace.edu</p>
	</div>

	</td>
	<td>
    </td>
</tr>
<tr>
<td><img src="<?php echo $CFG['wwwroot']; ?>/images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
<td></td>
</tr>
</table>
 
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
	
	


