<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act         = optional_param('act', NULL, PARAM_TEXT);
$fullname    = optional_param('fullname', NULL, PARAM_TEXT);
$shortname   = optional_param('shortname', NULL, PARAM_TEXT);
$summary     = optional_param('summary', NULL, PARAM_TEXT);
$id          = optional_param('id', NULL, PARAM_INT);
$guest       = optional_param('guest', 0, PARAM_INT);

if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}course WHERE id = ".$id);

if ($fullname) {
    $soundtexttype = 0;
    
    $datam              = array();
    $datam['fullname']  = $fullname;
    $datam['shortname'] = $shortname;
    $datam['summary']   = $summary;
    $datam['guest']     = $guest;
     
    if ($id) {
        $datam['id'] = $id;
    }
    
    $id = insert_record("course", $datam);

    $status = statusmessage($fullname.' - Added');
    
}

if ($act == "edit" && !empty($id))
    $data = get_record("course", array("id"=>$id));
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





	<!--  start page-heading -->
	<div id="page-heading">
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> new course</h1>
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
			<div class="step-dark-left"><a href="#">Add course details</a></div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=createcourse" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Name:</th>
			<td><input type="text" name="fullname" class="inp-form-error" value="<?php if (isset($data->fullname)) echo $data->fullname; ?>" /></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Shortname:</th>
			<td><input type="text" name="shortname" class="inp-form-error" value="<?php if (isset($data->shortname)) echo $data->shortname; ?>" /></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Summary:</th>
			<td><textarea class="form-textarea" name="summary" cols="" rows="" class="inp-form-error"><?php if (isset($data->summary)) echo $data->summary; ?></textarea></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th>Allow guest:</th>
			<td>
		<select name="guest" class="styledselect_form_1">
			<option value="1" <?php if ($data->guest && $data->guest == 1) echo "selected=\"selected\""; ?> >Yes</option>
			<option value="0" <?php if ($data->guest && $data->guest == 0) echo "selected=\"selected\""; ?> >No</option>
		</select>
			</td>
			<td></td>
		
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
	



