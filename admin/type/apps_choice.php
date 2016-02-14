<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act        = optional_param('act', NULL, PARAM_TEXT);
$text       = optional_param('text', NULL, PARAM_TEXT);
$var        = optional_param('var', NULL, PARAM_TEXT);
$category   = optional_param('category', NULL, PARAM_TEXT);
$id         = optional_param('id', NULL, PARAM_INT);
$cor        = optional_param('cor', 0, PARAM_INT);

if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}apps_choice WHERE id = ".$id);


if ($text) {
    $imagetype = 0;
    $soundtexttype = 0;
    
    if ($_FILES['image']['tmp_name'] || is_file($CFG['dirroot'].'/datas/choice/image/'.$id.'.jpg') || is_file($CFG['dirroot'].'/datas/choice/image/'.$id.'.png')) $imagetype = 1;
    if ($_FILES['soundtext']['tmp_name'] || is_file($CFG['dirroot'].'/datas/choice/soundtext/'.$id.'.mp3')) $soundtexttype = 1;
    
    
    $datam              = array();
    $datam['text']      = $text;
    $datam['image']     = $imagetype;
    $datam['soundtext'] = $soundtexttype;
    $datam['category']  = $category;
     
    while(list($key,$value)=each($var)) {
      if (!empty($value))
        $datam['var'.$key]  = $value;
    }
    reset($var);
    
    while(list($key,$value)=each($cor)) {
      if (!empty($value))
        $datam['cor'.$key]  = $value;
    }
    reset($cor);
     
    if ($id) 
        $datam['id'] = $id;
    
    $id = insert_record("apps_choice", $datam);
    
    if ($_FILES['image']['tmp_name']) {
        list($width, $height, $type, $attr) = getimagesize($_FILES['image']['tmp_name']);
        if ($width > 180 || $height > 180) {
            $image = new SimpleImage();
            $image->load($_FILES['image']['tmp_name']);
            if ($width > $height) {
                $image->resizeToWidth(180);
            }
            else
            {
                $image->resizeToHeight(180);
            }
            $image->save($CFG['dirroot']."/datas/choice/image/{$id}.jpg");
        }
        else
        {
            move_uploaded_file ($_FILES['image']['tmp_name'], $CFG['dirroot']."/datas/choice/image/{$id}.jpg");
        }
    }
    
    if ($_FILES['soundtext']['tmp_name']) move_uploaded_file ($_FILES['soundtext']['tmp_name'], $CFG['dirroot']."/datas/choice/soundtext/{$id}.mp3");
    
    $status = statusmessage('Choice - Added');
    
}

if ($act == "edit" && !empty($id))
    $data = get_record("apps_choice", array("id"=>$id));
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> choice item</h1>
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
			<div class="step-dark-left"><a href="#">Add choice item details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="http://webapp.netcourse.org/products.php#choice" target="_blank">Fields help</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=apps_choice" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Question text:</th>
			<td><textarea class="form-textarea" name="text" cols="" rows="" class="inp-form-error"><?php if (isset($data->text)) echo $data->text; ?></textarea> <br style="margin-bottom:10px;"><span class="example">Example: Where is his hometown?</span></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Category:</th>
			<td><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: Unit 1</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_choice&",
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
			<th>Audio:</th>
			<td><input type="file" name="soundtext" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->soundtext)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/choice/soundtext/'.$id.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>

			</td>
		
		</tr>
		<tr>
			<th>Image:</th>
			<td>  <input type="file" name="image" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->image)) echo '<div style="margin-left:20px;"><a href="'.printimage('/datas/choice/image/'.$id).'" target="_blank"><img src="'.printimage('/datas/choice/image/'.$id).'?'.time().'" height="60" /></a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">JPEG, PNG 100Kb max per image (<a href="<?php echo $CFG['wwwroot']."/images/image-example.jpg"; ?>" target="_blank">Example</a>)</div>
			<div class="bubble-right"></div>
			<?php } ?>
			</td>
		
		</tr>
		
<?php
        for ($i=1;$i<=12;$i++) {
          $vard = "var".$i;
          $cord = "cor".$i;
        ?>
		<tr>
			<th valign="top">Choice <?php echo $i; ?>:</th>
			<td><input type="text" name="var[<?php echo $i; ?>]" class="inp-form" value="<?php if (isset($data->$vard)) echo $data->$vard; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: He has two sisters.</span></td>
			<td>check if correct <input type="checkbox" name="cor[<?php echo $i; ?>]" value="1" <?php if (!empty($data->$cord)) echo 'checked'; ?>  /></td>
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
	
