<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$id         = optional_param('id', NULL, PARAM_INT);
$act        = optional_param('act', NULL, PARAM_TEXT);
$apps       = optional_param('apps', NULL, PARAM_TEXT);
$category   = optional_param('category', NULL, PARAM_TEXT);

$category = urldecode($category);


if ($act == "delete") {
    $db->query("DELETE FROM {$CFG['prefix']}{$apps} WHERE `category` = '".$category."'");
    
    switch ($apps) {
        case 'apps_choice':
            $i = 1;
            break;
        case 'apps_listening':
            $i = 4;
            break;
        case 'apps_quizzes':
            $i = 5;
            break;
        case 'apps_resource':
            $i = 6;
            break;
        case 'apps_reading':
            $i = 3;
            break;
        case 'apps_vocabulary':
            $i = 2;
            break;
    }
    
    $topicdata = get_records("course_topic", array(), '`order`');
    if (is_object($topicdata) || is_array($topicdata)) {
      while(list($key,$value)=each($topicdata)) {
        $activity = json_decode($value->data, true);

        foreach($activity as $k=>$v){
          $ik = key($v);
          $iv = current($v);
          if($ik == $i && $iv == $category) {
            unset($activity[$k]);
          }
        }
        
        $datam              = array();
        $datam['id']        = $value->id;
        $datam['data']      = json_encode($activity);
        
        $idt = insert_record("course_topic", $datam);
      }
    }
  
}

?>
		
<script type="text/javascript">
$(document).ready(function() {
    $('#list-table').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"iDisplayLength": <?php echo $options['defaultentries']; ?>
    });
    
    $('.quickeditor-text').dblclick(function(){ 
      $(this).html('<input type="text" name="quickedit" value="'+$(this).text()+'" class="quickeditor-input" style="width: 300px;" onkeypress="quick_editor_text_submit(event,this);" />');
    });

    $('.fg-toolbar').click(function() {
      $('.quickeditor-text').dblclick(function(){ 
        $(this).html('<input type="text" name="quickedit" value="'+$(this).text()+'" class="quickeditor-input" style="width: 300px;" onkeypress="quick_editor_text_submit(event,this);" />');
      });
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

function quick_editor_text_submit(e,i){
  if(e.keyCode==13) {
    var p = $(i).parent();
    
    console.log(p.attr('data-value'));
    console.log(i.value);
    
    jQuery.get('ajax.php', {a: 'quick-title-edit', value: p.attr('data-value'), text: i.value}, function(data) {
      
    });
    
    p.html(i.value);
  }
}

</script>

	<!--  start page-heading -->
	<div id="page-heading">
		<h1>List of available content</h1>
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
if ($act != "details") {
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="list-table"> 
<thead> 
<tr>
    <th width="150">Category</th> 
    <th width="150">Application</th> 
    <th width="50">Items</th> 
    <th width="50">Delete</th> 
</tr>
</thead> 
<tbody> 
<?php
$c = 0;

while (list($key,$value)=each($appstables)) {
  $data = get_records($value, array(), '`category`', 'DISTINCT category,id');
 
  while(list($key2,$value2)=each($data)) {
    $c++;
    
    $items = get_records($value, array("category" => $value2->category));
    
    echo '<tr id="item-'.$c.'"> 
      <td class="quickeditor-text" data-value="'.$value2->category.'::'.$value.'">'.$value2->category.'</td>
      <td>'.$appsnewnames[$key].'</td> 
      <td><a href="index.php?type=contents&act=details&apps='.$value.'&category='.urlencode($value2->category).'">'.count($items).'</a></td> 
      <td><a href="#" onclick="deleterow(\'index.php?type=contents&act=delete&apps='.$value.'&category='.urlencode($value2->category).'\', \'item-'.$c.'\'); return false;">delete</a></td> 
    </tr>';
  }
}
reset($appstables);


?>
</tbody><tfoot>
<tr>
    <th width="150">Category</th> 
    <th width="150">Application</th> 
    <th width="50">Items</th> 
    <th width="50">Delete</th> 
</tr>
</tfoot>
</table>

<?php
} else {
  ?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="list-table"> 
<thead> 
<tr>
    <th width="300">Title</th> 
    <th width="150">Category</th> 
    <th width="150">Application</th> 
    <th width="50">Edit</th> 
    <th width="50">Delete</th> 
</tr>
</thead> 
<tbody> 
<?php
$c = 0;

$data = get_records($apps, array("category" => $category));

while(list($key,$value)=each($data)) {
  $c++;
  $appstables3 = array_flip($appstables);
  echo '<tr id="item-'.$c.'"> 
    <td>'.substr($value->$appsmainfield[$apps], 0, 70).'</td>
    <td>'.$category.'</td>
    <td>'.$appsnewnames[$appstables3[$apps]].'</td> 
    <td><a href="?type='.$apps.'&act=edit&id='.$value->id.'" target="_blank">edit</a></td> 
    <td><a href="#" onclick="deleterow(\'?type='.$apps.'&act=delete&id='.$value->id.'\', \'item-'.$c.'\')">delete</a></td> 
  </tr>';
}

?>
</tbody><tfoot>
<tr>
    <th width="300">Title</th> 
    <th width="150">Category</th> 
    <th width="150">Application</th> 
    <th width="50">Edit</th> 
    <th width="50">Delete</th> 
</tr>
</tfoot>
</table>
  <?php
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
	


