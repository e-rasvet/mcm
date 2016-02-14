<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$id         = optional_param('id', NULL, PARAM_INT);
$courseid   = optional_param('courseid', NULL, PARAM_INT);

?>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#list-table').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"iDisplayLength": <?php echo $options['defaultentries']; ?>
    });
});
    
function deleterow(link, row) {
    if(confirm('Delete?')) {
        $('#'+row).remove();
        $.post(link);
        return false;
    }else {
        return false;
    }
    
}
</script>






	<!--  start page-heading -->
	<div id="page-heading">
		<h1>List of avaiable users</h1>
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
		
<table cellpadding="0" cellspacing="0" border="0" class="display" id="list-table"> 
<thead> 
<tr>
    <th width="150">Username</th> 
    <th width="150">Firstname</th> 
    <th width="150">Lastname</th> 
    <th width="150">Email</th> 
    <th width="50">Activity</th> 
    <th width="50">Edit</th> 
    <th width="50">Delete</th> 
</tr>
</thead> 
<tbody> 
<?php
$c = 0;

if (!empty($courseid)) {
  $students = get_records("course_student", array("courseid" => $courseid));
  while(list($key,$value)=each($students)) {
    $data[] = get_record("user", array("id" => $value->userid));
  }
} else
  $data = get_records("user");


while(list($key,$value)=each($data)) {
$c++;
  echo '<tr id="item-'.$c.'"> 
    <td>'.$value->username.'</td>
    <td>'.$value->firstname.'</td>
    <td>'.$value->lastname.'</td>
    <td>'.$value->email.'</td>
    <td><a href="?type=addusers&act=edit&id='.$value->id.'">edit</a></td> 
    <td><a href="?type=studentsactivity&id='.$value->id.'&courseid='.$courseid.'">activity</a></td> 
    <td><a href="#" onclick="deleterow(\'?type=addusers&act=delete&id='.$value->id.'\', \'item-'.$c.'\')">delete</a></td> 
  </tr>';
}

?>
</tbody><tfoot>
<tr>
    <th width="150">Username</th> 
    <th width="150">Firstname</th> 
    <th width="150">Lastname</th> 
    <th width="150">Email</th> 
    <th width="50">Activity</th> 
    <th width="50">Edit</th> 
    <th width="50">Delete</th> 
</tr>
</tfoot>
</table>

		 
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
	
	
