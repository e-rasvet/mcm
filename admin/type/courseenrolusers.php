<?php

include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$id         = optional_param('id', NULL, PARAM_INT);
$students   = optional_param('students', NULL, PARAM_INT);

$data = get_record("course", array("id"=>$id));

if (!isset($data->id))
  die("Course not exist");


if (is_array($students)) {
  $db->query("DELETE FROM {$CFG['prefix']}course_student WHERE courseid = ".$id);
  
  while (list($key,$value)=each($students)) {
    $datam              = array();
    $datam['userid']    = $value;
    $datam['courseid']  = $id;
    insert_record("course_student", $datam);
  }
  
  $status = statusmessage('Students list was updated');
}


$preselect = "0,";

if ($currentstudents = get_records("course_student", array("courseid" => $id))) {
  while(list($key,$value)=each($currentstudents)) {
    $preselect .= $value->userid.',';
  }
}

$preselect = substr($preselect, 0, -1);
  
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".studentselect").listselect();
	});
</script>



	<!--  start page-heading -->
	<div id="page-heading">
		<h1>Add students to <?php echo $data->fullname; ?> course</h1>
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
		
		<form id="formadd" name="formadd_point" method="post" action="?type=courseenrolusers&id=<?php echo $id; ?>">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top"></th>
			<td colspan="2">
			
			<?php if(isset($status)) echo $status; ?>
			
	<select class="studentselect" name="students[]" selected="<?php echo $preselect; ?>">
<?php
$students = get_records("user");
while (list($key,$value)=each($students)) {
?>
		<option value="<?php echo $value->id; ?>"><?php echo $value->username.' ('.$value->firstname.' '.$value->lastname.')'; ?></option>
<?php } ?>
	</select>
			</td>
		</tr>
	<tr>
		<th>&nbsp;</th>
		<td valign="top">
			<input type="submit" value="" class="form-submit" />
		</td>
		<td></td>
	</tr>
	</table>
</form>


		
		
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
	


