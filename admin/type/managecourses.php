<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$id         = optional_param('id', NULL, PARAM_INT);
$topic     = optional_param('topic', NULL, PARAM_TEXT);

$data = get_record("course", array("id"=>$id));

if (((int)$options['_limitbefore'] - 20 * 24 * 3600) < time()) {
  $timediff = $options['_limitbefore'] - time();
  if ($timediff > 0) {
    $daystime = round($timediff / (24 * 3600));
  
    echo '<div style="color:green;padding:8px;font-size:16px;">You trial period will be over in '.$daystime.' days <a href="'.$mcm_server.'/buy.php?type=mcm" target="_blank">(buy the script)</a> <a href="http://webapp.netcourse.org/quickstart.php" target="_blank">QUICK START GUIDE</a></div>';
  }
}

if (!empty($_POST['button']))
  echo statusmessage('Changes saved');


if (!isset($data->id)) {
?>

	<!--  start page-heading -->
	<div id="page-heading">
		<h1>List of courses</h1>
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
		$data = get_records("course");
		while(list($key,$value)=each($data)) {
		  echo '<div style="padding:10px;"><a href="index.php?type=managecourses&id='.$value->id.'" class="link-h-1">'.$value->fullname.' ('.$value->shortname.')</a></div>';
		} 
		?>
		
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
	


<?php
} else {


if (is_array($topic)) {
  while (list($key,$value)=each($_POST)) {
    if (strstr($key, "topicactivitys_") && !empty($value)) {
      $activitydata = str_replace("topicactivitys_", "", $key);
      list($l1, $l2, $l3)  = explode("_", $activitydata);
      $activityarr[$l1][$l2][$l3] = $value; //addslashes($value);
    }
  }
  reset($_POST);
  
  $db->query("DELETE FROM {$CFG['prefix']}course_topic WHERE courseid = ".$id);
  
  $c = 0;
  
  while(list($key,$value)=each($topic)) {
    $c++;
    $datam              = array();
    $datam['title']     = $value;
    $datam['courseid']  = $id;
    $datam['data']      = json_encode($activityarr[$key]);
    $datam['order']     = $c;
    
    $idt = insert_record("course_topic", $datam);
  }
}


$selectorhtml = '<select name=\"addnewtopic\" id=\"topic_category_"+id+"_1\" onChange=\"topic_sub_category("+id+", 1)\"><option value=\"0\">Select app</option>';
reset($appstables2);
while (list($key,$value)=each($appstables2)) {
  $selectorhtml .= '<option value=\"'.$key.'\">'.$value.'</option>';
}
$selectorhtml .= '</select>';
reset($appstables2);

$selectorhtml_other = '<select name=\"addnewtopic\" id=\"topic_category_"+id+"_"+idt+"\" onChange=\"topic_sub_category("+id+", "+idt+")\"><option value=\"0\">Select app</option>';
reset($appstables2);
while (list($key,$value)=each($appstables2)) {
  $selectorhtml_other .= '<option value=\"'.$key.'\">'.$value.'</option>';
}
$selectorhtml_other .= '</select>';
reset($appstables2);

?>

<script type="text/javascript" charset="utf-8">

function addFormField() {
	var id = document.getElementById("idmark").value;
	id = (id - 1) + 2;
	$("#formpoint ul").append("<li id=\"topic_box_"+id+"\"><input id=\"idmark_topic_"+id+"\" type=\"hidden\" value=\"1\" /><div style=\"background:#eee;padding:10px;margin:10px;\" id=\"topic_container_"+id+"\"><div style=\"position:absolute;padding-left:700px;\"><a style=\"cursor:move\"><img hspace=\"3\" src=\"<?php echo $CFG['wwwroot']; ?>/images/move.gif\" ></a></div><div style=\"position: absolute; margin-left: 740px;\"><a href=\"#\" onclick=\"delete_topic("+id+");return false;\"><img src=\"<?php echo $CFG['wwwroot']; ?>/images/delete-topic.png\" width=\"16\" height=\"16\"/></a></div><div style=\"color:#aaa;\">Topic "+id+"</div><div style=\"float:left;padding-right:10px;padding:10px 0;\">Title:</div><div style=\"float:left;padding:10px 0;\"><input type=\"text\" name=\"topic["+id+"]\" value=\"Topic "+id+"\" id=\"topic_name_"+id+"\" /></div><div style=\"clear:both;\"></div><ul id=\"topic_activity_"+id+"\"><li id=\"topic_activity_item_"+id+"_1\"><div style=\"float:left;padding-right:10px;padding-top:2px;\"><a href=\"#\" onclick=\"delete_activity_item("+id+", 1);return false;\"><img src=\"<?php echo $CFG['wwwroot']; ?>/images/delete-act.png\" width=\"16\" height=\"16\"/></a></div><div style=\"float:left;padding-right:10px;\">Application</div><div style=\"float:left;padding-right:10px;\"><?php echo $selectorhtml; ?></div><div style=\"float:left;padding-right:10px;\" id=\"topic_sub_category_"+id+"_1\"></div><div style=\"clear:both;\"></div></li></ul><div><a href=\"#\" onclick=\"add_topic_activity("+id+");return false;\"  style=\"color:#92B22C;text-decoration:underline;padding:10px;\">Add activity</a></div></div></li>");
	document.getElementById("idmark").value = id;
}

function add_topic_activity(id) {
	var idt = document.getElementById("idmark_topic_"+id).value;
	idt = (idt - 1) + 2;
	$("#topic_activity_"+id).append("<li id=\"topic_activity_item_"+id+"_"+idt+"\"><div style=\"float:left;padding-right:10px;padding-top:2px;\"><a href=\"#\" onclick=\"delete_activity_item("+id+", "+idt+");return false;\"><img src=\"<?php echo $CFG['wwwroot']; ?>/images/delete-act.png\" width=\"16\" height=\"16\"/></a></div><div style=\"float:left;padding-right:10px;\">Application</div><div style=\"float:left;padding-right:10px;\"><?php echo $selectorhtml_other; ?></div><div style=\"float:left;padding-right:10px;\" id=\"topic_sub_category_"+id+"_"+idt+"\"></div><div style=\"clear:both;\"></div></li>");
	document.getElementById("idmark_topic_"+id).value = idt;
}

function topic_sub_category(id,idt) {
  if ($("#topic_category_"+id+"_"+idt).val() != 0) {
	$('#topic_sub_category_'+id+"_"+idt).html('<img src="<?php echo $CFG['wwwroot']; ?>/images/ajax-loader.gif" width="16" height="16" alt="loading"/>');
	$.post('<?php echo $CFG['wwwroot']; ?>/admin/type/managecourses_ajax.php', { act: "loadtopicact", apps: $("#topic_category_"+id+"_"+idt).val(), name: id+"_"+idt }, function(data) {
      $('#topic_sub_category_'+id+"_"+idt).html(data);
    });
  } else {
    $('#topic_sub_category_'+id+"_"+idt).html("");
  }
}

function delete_topic(id) {
    if(confirm('Delete?')) {
        $('#topic_container_'+id).remove();
        return false;
    }else {
        return false;
    }
}

function delete_activity_item(id,idt) {
    if(confirm('Delete?')) {
        $('#topic_activity_item_'+id+'_'+idt).remove();
        return false;
    }else {
        return false;
    }
}

$(document).ready(function(){ 

	$(function() {
		$("#formpoint ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
		
			var order = $(this).sortable("serialize") + '&act=updatetopiclist&id=<?php echo $id; ?>';
			$.post("<?php echo $CFG['wwwroot']; ?>/admin/type/managecourses_ajax.php", order, function(theResponse){
				//$("#contentRight").html(theResponse);
			});
		}
		});
	});

});

</script> 


<style>
#formpoint ul {
list-style-type: none;
}
</style>



	<!--  start page-heading -->
	<div id="page-heading">
		<h1>Course <?php echo $data->fullname."(".$data->shortname.")" ?></h1>
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
	

		
<div id="formpoint">
        <form id="formadd" name="formadd_point" method="post" action="?type=managecourses&id=<?php echo $id; ?>">
        
        <div style="padding:0 20px;"><center><input name="button" type="submit" value="Apply changes" /></center></div>
        
        <ul>
        
        <?php 
        $topicdata = get_records("course_topic", array("courseid" => $id), '`order`');
        $c = 0;
        $j = 0;
        if (is_object($topicdata) || is_array($topicdata)) {
        while(list($key,$value)=each($topicdata)) {
          $activity = json_decode($value->data);
          $c++;
?>
<li id="topic_box_<?php echo $c; ?>">
<div style="background: none repeat scroll 0% 0% rgb(238, 238, 238); padding: 10px; margin: 10px;" id="topic_container_<?php echo $c; ?>">
<div style="position: absolute; padding-left: 700px;">
<a style="cursor: move;"><img src="<?php echo $CFG['wwwroot']; ?>/images/move.gif" hspace="3"></a>
</div>
<div style="position: absolute; margin-left: 740px;">
<a href="#" onclick="delete_topic(<?php echo $c; ?>);return false;"><img src="<?php echo $CFG['wwwroot']; ?>/images/delete-topic.png" height="16" width="16"></a>
</div>
<div style="color:#aaa;">Topic <?php echo $c; ?></div>
<div style="float: left; padding: 10px 0pt;">Title:</div>
<div style="float: left; padding: 10px 0pt;"><input name="topic[<?php echo $c; ?>]" value="<?php echo $value->title; ?>" id="topic_name_<?php echo $c; ?>" type="text"></div>
<div style="clear: both;"></div>
<ul id="topic_activity_<?php echo $c; ?>">

<?php 

if (is_object($activity) || is_array($activity)) {
while(list($activitykey,$activityvalue)=each($activity)) {
  $j++;
  $actid = key($activityvalue);
  $actcategory = $activityvalue->$actid;
  $activitykey = $j;
?>
<li id="topic_activity_item_<?php echo $c; ?>_<?php echo $activitykey; ?>">
<div style="float: left; padding-right: 10px; padding-top: 2px;">
<img src="<?php echo $CFG['wwwroot']; ?>/images/move.gif" hspace="3">
<a href="#" onclick="delete_activity_item(<?php echo $c; ?>, <?php echo $activitykey; ?>);return false;">
<img src="<?php echo $CFG['wwwroot']; ?>/images/delete-act.png" height="16" width="16"></a>
</div>
<div style="float: left; padding-right: 10px;">Application</div>
<div style="float: left; padding-right: 10px;">
<select name="addnewtopic" id="topic_category_<?php echo $c; ?>_<?php echo $activitykey; ?>" onchange="topic_sub_category(<?php echo $c; ?>, <?php echo $activitykey; ?>)">
<?php
reset($appstables2);
  while (list($key2,$value2)=each($appstables2)) {
    echo '<option value="'.$key2.'" ';
    if ($actid == $key2) echo ' selected="selected" ';
    echo ' >'.$value2.'</option>';
  }
reset($appstables2);
?>

</select>
</div>
<div style="float: left; padding-right: 10px;" id="topic_sub_category_<?php echo $c; ?>_<?php echo $activitykey; ?>">

<select name="topicactivitys_<?php echo $c; ?>_<?php echo $j; ?>_<?php echo $actid; ?>">
<?php

  if ($actid == 1) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_choice ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 2) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_vocabulary ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 3) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_reading ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 4) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_listening ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 5) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_quizzes ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 6) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_resource ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 7) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_clozeactivity ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 8) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_dragdrope ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 9) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_ordering ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 10) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_listenspeak ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  } else if ($actid == 11) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_groupquiz ORDER BY `category`",OBJECT);
    while (list($key3,$value3)=each($data)) {
      echo '<option value="'.$value3->category.'" ';
      if ($actcategory == $value3->category) echo ' selected="selected" ';
      echo ' >'.$value3->category.'</option>';
    }
  }

    switch ($actid) {
        case 1:
            $acttext = 'apps_choice';
            break;
        case 4:
            $acttext = 'apps_listening';
            break;
        case 5:
            $acttext = 'apps_quizzes';
            break;
        case 3:
            $acttext = 'apps_reading';
            break;
        case 2:
            $acttext = 'apps_vocabulary';
            break;
        case 6:
            $acttext = 'apps_resource';
            break;
        case 7:
            $acttext = 'apps_clozeactivity';
            break;
        case 8:
            $acttext = 'apps_dragdrope';
            break;
        case 9:
            $acttext = 'apps_ordering';
            break;
        case 10:
            $acttext = 'apps_listenspeak';
            break;
        case 11:
            $acttext = 'apps_groupquiz';
            break;
    }
    

?>
</select>

</div>

<a href="<?php echo $CFG['wwwroot']; ?>/admin/index.php?type=contents&act=details&apps=<?php echo $acttext; ?>&category=<?php echo str_replace(" ", "+", $actcategory);?>"><img src="<?php echo $CFG['wwwroot']; ?>/images/edit-text.png" hspace="3" style="width:18px" /></a>

<div style="clear: both;"></div>
</li>

<?php 
  $lastactivitykey = $activitykey;

}
  $nextactkey = $lastactivitykey + 1;
} else {
  $nextactkey = 2;
}

?>

<li id="topic_activity_item_<?php echo $c; ?>_<?php echo $nextactkey; ?>">
<div style="float: left; padding-right: 10px; padding-top: 2px;"><a href="#" onclick="delete_activity_item(<?php echo $c; ?>, <?php echo $nextactkey; ?>);return false;"><img src="<?php echo $CFG['wwwroot']; ?>/images/delete-act.png" height="16" width="16"></a></div>
<div style="float: left; padding-right: 10px;">Application</div>
<div style="float: left; padding-right: 10px;"><select name="addnewtopic" id="topic_category_<?php echo $c; ?>_<?php echo $nextactkey; ?>" onchange="topic_sub_category(<?php echo $c; ?>, <?php echo $nextactkey; ?>)"><option value="0">Select app</option>
<?php
reset($appstables2);
while(list($key,$value)=each($appstables2)) {
  echo '<option value="'.$key.'">'.$value.'</option>';
}
reset($appstables2);
?>
</select></div>
<div style="float: left; padding-right: 10px;" id="topic_sub_category_<?php echo $c; ?>_<?php echo $nextactkey; ?>"></div>
<div style="clear: both;"></div></li>

</ul><div><a href="#" onclick="add_topic_activity(<?php echo $c; ?>);return false;" style="color:#92B22C;text-decoration:underline;padding:10px;">Add activity</a></div>
</div>

<input id="idmark_topic_<?php echo $c; ?>" value="<?php echo $j + 1; ?>" type="hidden">

</li>
<?php
        }
        } else {
          echo '<li>No topics yet.</li>';
        }
        
        ?>
        
        </ul>
        
        <div><a href="#" onclick="addFormField();return false;"  style="color:#92B22C;text-decoration:underline;padding:10px;">Add new topic</a></div>
        
        <div style="padding:0 20px;"><center><input name="button" type="submit" value="Apply changes" /></center></div>
        
        </form><input id="idmark" type="hidden" value="<?php echo $c; ?>" />
      </div>
       
    


	</td>
	<td>

	<!--  start related-activities -->
	<div id="related-activities">
		
		<!--  start related-act-top -->
		<div id="related-act-top">

		<img src="<?php echo $CFG['wwwroot']; ?>/images/forms/header_related_act.gif" width="271" height="43" alt="" />
		</div>
		<!-- end related-act-top -->
		
		<!--  start related-act-bottom -->
		<div id="related-act-bottom">
		
			<!--  start related-act-inner -->
			<div id="related-act-inner">

				<div class="left"><a href=""><img src="<?php echo $CFG['wwwroot']; ?>/images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
				<div class="right">
					<h5>Edit course</h5>
					<ul class="greyarrow">
						<li><a href="<?php echo $CFG['wwwroot']; ?>/admin/index.php?type=courseenrolusers&id=<?php echo $id; ?>">Enrol students</a></li> 
						<li><a href="<?php echo $CFG['wwwroot']; ?>/admin/index.php?type=createcourse&act=edit&id=<?php echo $id; ?>">Edit course</a> </li>
						<li><a href="<?php echo $CFG['wwwroot']; ?>/admin/index.php?type=listofusers&courseid=<?php echo $id; ?>">Students activity</a> </li>
						<li><a href="http://webapp.netcourse.org/products.php#managecourse" target="_blank">Fields Help</a></li>
					</ul>
				</div>
				<div class="clear"></div>
				
			</div>
			<!-- end related-act-inner -->
			<div class="clear"></div>

		
		</div>
		<!-- end related-act-bottom -->
	
	</div>
	<!-- end related-activities -->
	
	
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
	
<?php } ?>