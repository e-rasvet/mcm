<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$id         = optional_param('id', NULL, PARAM_INT);

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
		<h1>List of avaiable courses</h1>
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
    <th width="150">Fullname</th> 
    <th width="150">Shortname</th> 
    <th width="150">Summary</th> 
    <th width="50">Enrol students</th> 
    <th width="50">Manage</th> 
    <th width="50">Edit</th> 
    <th width="50">Delete</th> 
</tr>
</thead> 
<tbody> 
<?php
$c = 0;

$data = get_records("course");


while(list($key,$value)=each($data)) {
$c++;
  echo '<tr id="item-'.$c.'"> 
    <td>'.$value->fullname.'</td>
    <td>'.$value->shortname.'</td>
    <td>'.substr($value->summary,0,70).'</td>
    <td><a href="?type=courseenrolusers&id='.$value->id.'">enrol</a></td>
    <td><a href="?type=managecourses&id='.$value->id.'">manage</a></td>
    <td><a href="?type=createcourse&act=edit&id='.$value->id.'">edit</a></td> 
    <td><a href="#" onclick="deleterow(\'?type=createcourse&act=delete&id='.$value->id.'\', \'item-'.$c.'\')">delete</a></td> 
  </tr>';
}

?>
</tbody><tfoot>
<tr>
    <th width="150">Fullname</th> 
    <th width="150">Shortname</th> 
    <th width="150">Summary</th> 
    <th width="50">Enrol students</th> 
    <th width="50">Manage</th> 
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
	



