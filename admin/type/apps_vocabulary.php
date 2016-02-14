<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act        = optional_param('act', NULL, PARAM_TEXT);
$word       = optional_param('word', NULL, PARAM_TEXT);
$translate  = optional_param('translate', NULL, PARAM_TEXT);
$category   = optional_param('category', NULL, PARAM_TEXT);
$text       = optional_param('text', NULL, PARAM_TEXT);
$id         = optional_param('id', NULL, PARAM_INT);

if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}apps_vocabulary WHERE id = ".$id);


if ($word) {
    $imagetype = 0;
    $soundwordtype = 0;
    $soundtexttype = 0;
    $videotype = 0;
    
    if ($_FILES['image']['tmp_name'] || is_file($CFG['dirroot'].'/datas/vocab/image/'.$id.'.jpg') || is_file($CFG['dirroot'].'/datas/vocab/image/'.$id.'.png')) $imagetype = 1;
    if ($_FILES['soundword']['tmp_name'] || is_file($CFG['dirroot'].'/datas/vocab/soundword/'.$id.'mp3')) $soundwordtype = 1;
    if ($_FILES['soundtext']['tmp_name'] || is_file($CFG['dirroot'].'/datas/vocab/soundtext/'.$id.'.mp3')) $soundtexttype = 1;
    if ($_FILES['video']['tmp_name'] || is_file($CFG['dirroot'].'/datas/vocab/video/'.$id.'.m4v')) $videotype = 1;
    
    
    $datam              = array();
    $datam['word']      = $word;
    $datam['translate'] = $translate;
    $datam['text']      = $text;
    $datam['image']     = $imagetype;
    $datam['soundword'] = $soundwordtype;
    $datam['soundtext'] = $soundtexttype;
    $datam['video']     = $videotype;
    $datam['category']  = $category;
     
    if ($id) {
        $datam['id'] = $id;
    }
    
    $id = insert_record("apps_vocabulary", $datam);
    
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
            $image->save($CFG['dirroot']."/datas/vocab/image/{$id}.jpg");
        }
        else
        {
            move_uploaded_file ($_FILES['image']['tmp_name'], $CFG['dirroot']."/datas/vocab/image/{$id}.jpg");
        }
    }
    
    if ($_FILES['soundword']['tmp_name']) move_uploaded_file ($_FILES['soundword']['tmp_name'], $CFG['dirroot']."/datas/vocab/soundword/{$id}.mp3");
    if ($_FILES['soundtext']['tmp_name']) move_uploaded_file ($_FILES['soundtext']['tmp_name'], $CFG['dirroot']."/datas/vocab/soundtext/{$id}.mp3");
    if ($_FILES['video']['tmp_name']) move_uploaded_file ($_FILES['video']['tmp_name'], $CFG['dirroot']."/datas/vocab/video/{$id}.m4v");
    
    $status = statusmessage($word.' - Added');
}

if ($act == "edit" && !empty($id))
    $data = get_record("apps_vocabulary", array("id"=>$id));
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> vocabulary item</h1>
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
			<div class="step-dark-left"><a href="#">Add vocabulary item details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="http://webapp.netcourse.org/products.php#vocabulary" target="_blank">Fields help</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=apps_vocabulary" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">


		<tr>
			<th valign="top">Word:</th>
			<td><input type="text" name="word" class="inp-form-error" value="<?php if (isset($data->word)) echo $data->word; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: accept</span></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Translation:</th>
			<td><input type="text" name="translate" class="inp-form-error" value="<?php if (isset($data->translate)) echo $data->translate; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: （～を）受け入れる、受諾する</span></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Text:</th>
			<td><input type="text" name="text" class="inp-form-error" value="<?php if (isset($data->text)) echo $data->text; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: I accept your offer. あなたの申し入れをお受けします。</span></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Category:</th>
			<td><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: UNIT 1</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_vocabulary&",
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
			<th>Image:</th>
			<td><input type="file" name="image" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->image)) echo '<div style="margin-left:20px;"><a href="'.printimage('/datas/vocab/image/'.$id).'" target="_blank"><img src="'.printimage('/datas/vocab/image/'.$id).'?'.time().'" height="60" /></a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">JPEG, PNG 100Kb max per image (<a href="<?php echo $CFG['wwwroot']."/images/image-example.jpg"; ?>" target="_blank">Example</a>)</div>
			<div class="bubble-right"></div>
			<?php } ?>
			</td>
		
		</tr>
		<tr>
			<th>Audio for Word:</th>
			<td width="290"><input type="file" name="soundword" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->soundword)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/vocab/soundword/'.$id.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>
			</td>
		</tr>
		<tr>
			<th>Audio for Text:</th>
			<td width="290"><input type="file" name="soundtext" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->soundtext)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/vocab/soundtext/'.$id.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>

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
	

