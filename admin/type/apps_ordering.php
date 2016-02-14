<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act        = optional_param('act', NULL, PARAM_TEXT);
$var        = optional_param('var', NULL, PARAM_TEXT);
$category   = optional_param('category', NULL, PARAM_TEXT);
$question   = optional_param('question', NULL, PARAM_TEXT);
$id         = optional_param('id', NULL, PARAM_INT);

if ($act == "delete" && !empty($id)) 
    $db->query("DELETE FROM {$CFG['prefix']}apps_ordering WHERE id = ".$id);


if ($var) {
    $soundtexttype = 0;
    $text = '';
    
    if ($_FILES['soundtext']['tmp_name'] || is_file($CFG['dirroot'].'/datas/ordering/soundtext/'.$id.'.mp3')) $soundtexttype = 1;
    
    $datam              = array();
    $datam['soundtext'] = $soundtexttype;
    $datam['category']  = $category;
     
    while(list($key,$value)=each($var)) {
      if (!empty($value)) {
        $datam['var'.$key]  = $value;
        $text .= $value.' ';
      }
    }
    reset($var);
     
    $datam['text']          = $text;
    $datam['question']      = $question;
     
    if ($id) 
        $datam['id'] = $id;
    
    $id = insert_record("apps_ordering", $datam);
    
    if ($_FILES['soundtext']['tmp_name']) move_uploaded_file ($_FILES['soundtext']['tmp_name'], $CFG['dirroot']."/datas/ordering/soundtext/{$id}.mp3");
    
    $status = statusmessage('ordering activity - Added');
}

if ($act == "edit" && !empty($id))
    $data = get_record("apps_ordering", array("id"=>$id));
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> ordering item</h1>
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
			<div class="step-dark-left"><a href="#">Add ordering activity item details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="http://webapp.netcourse.org/products.php#ordering" target="_blank">Fields help</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=apps_ordering" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Category:</th>
			<td style="width: 300px"><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: Unit 1</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_ordering&",
		varname:"input",
		json:true
	};
	var as_json = new AutoSuggest('catsuggest', options);
			</script>
			</div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Question:</th>
			<td style="width: 300px"><input type="text" name="question" class="inp-form-error" value="<?php if (isset($data->question)) echo $data->question; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: How often do you exercise?</span></td>
			<td>
			<div class="error-left">
			</div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th>Audio:</th>
			<td><input type="file" name="soundtext" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->soundtext)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/ordering/soundtext/'.$id.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>

			</td>
		
		</tr>
<?php
        for ($i=1;$i<=10;$i++) {
          $vard = "var".$i;
        ?>
		<tr>
			<th valign="top">Word <?php echo $i; ?>:</th>
			<td><input type="text" name="var[<?php echo $i; ?>]" class="inp-form" value="<?php if (isset($data->$vard)) echo $data->$vard; ?>" /> <br style="margin-bottom:10px;"><span class="example">is</span></td>
		</tr>
        <?php
        
        }
?>
		
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
	
