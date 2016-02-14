<?php

$id         = optional_param('id', NULL, PARAM_INT);
$courseid   = optional_param('courseid', 0, PARAM_INT);
/*

if ($layout == "course") {
  $data = get_records("course_student", array("courseid" => $id));
}

if (is_array($data) || is_object($data)) {
  while(list($key,$value)=each($data)) {
    
  }
}
*/

$student = get_record("user", array("id"=>$id));

?>



	<!--  start page-heading -->
	<div id="page-heading">
		<h1><?php echo "{$student->firstname} {$student->lastname} ({$student->username})" ?></h1>
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
		
		<?php
		
		while(list($key,$value)=each($appstables2)) {
		  if (!empty($courseid))
            $data = get_records("log", array("apps" => $key, "userid" => $id, "courseid" => $courseid));
          else
            $data = get_records("log", array("apps" => $key, "userid" => $id));
            
          if ($key == 1 || $key == 5) 
            $score = ", student score ".get_user_score($id, $key);
          else
            $score = "";
            
          echo '<div style="margin:10px;">'.$value.' - accessed '.count($data).' times'.$score.'</div>';
        }
		
		
		?>
 
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
	
	


