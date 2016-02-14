<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act        = optional_param('act', NULL, PARAM_TEXT);
$word       = optional_param('word', NULL, PARAM_TEXT);
$translate  = optional_param('translate', NULL, PARAM_TEXT);
$category   = optional_param('category', NULL, PARAM_TEXT);
$varwords   = optional_param('varwords', NULL, PARAM_TEXT);
$id         = optional_param('id', NULL, PARAM_INT);


if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}apps_quizzes WHERE id = ".$id);
    

if ($word) {
    $soundwordtype = 0;
    
    if ($_FILES['soundword']['tmp_name'] || is_file($CFG['dirroot'].'/datas/quiz/soundword/'.$id.'mp3')) $soundwordtype = 1;
    
    
    $datam              = array();
    $datam['word']      = $word;
    $datam['translate'] = $translate;
    $datam['soundword'] = $soundwordtype;
    $datam['category']  = $category;
     
    while(list($key,$value)=each($varwords)) {
      $datam['var'.($key + 1)]  = $value;
    }
    reset($varwords);
     
    if ($id) {
        $datam['id'] = $id;
    }
    
    $id = insert_record("apps_quizzes", $datam);
    
    while(list($key,$value)=each($varwords)) {
      if ($_FILES['varimage']['tmp_name'][$key]) {
        list($width, $height, $type, $attr) = getimagesize($_FILES['varimage']['tmp_name'][$key]);
        if ($width > 180 || $height > 180) {
            $image = new SimpleImage();
            $image->load($_FILES['varimage']['tmp_name'][$key]);
            if ($width > $height) {
                $image->resizeToWidth(180);
            }
            else
            {
                $image->resizeToHeight(180);
            }
            $image->save($CFG['dirroot']."/datas/quiz/image/{$id}_".($key + 1).".jpg");
        }
        else
        {
            move_uploaded_file ($_FILES['varimage']['tmp_name'][$key], $CFG['dirroot']."/datas/quiz/image/{$id}_".($key + 1).".jpg");
        }
      }
    }
    
    if ($_FILES['soundword']['tmp_name']) move_uploaded_file ($_FILES['soundword']['tmp_name'], $CFG['dirroot']."/datas/quiz/soundword/{$id}.mp3");
    
    $status = statusmessage($word.' - Added');
    
}

if ($act == "edit" && !empty($id))
    $data = get_record("apps_quizzes", array("id"=>$id));
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> quiz item</h1>
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
			<div class="step-dark-left"><a href="#">Add quiz item details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="http://webapp.netcourse.org/products.php#quiz" target="_blank">Fields help</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=apps_quizzes" onsubmit="return validate_form(this)">
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
			<th valign="top">Category:</th>
			<td><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: UNIT 1</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_quizzes&",
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
			<td width="290"><input type="file" name="soundword" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->soundword)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/quiz/soundword/'.$id.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>
			</td>
		
		</tr>
	
<?php
        for ($i=1;$i<=8;$i++) {
          $vard = "var".$i;
          $cord = "cor".$i;
          if ($vard == "var1") $s = ' (correct)'; else $s = "";
        ?>
		<tr>
			<th valign="top">Choice <?php echo $i.$s; ?>:</th>
			<td><input type="text" style="size:200px;" name="varwords[]" class="inp-form" value="<?php if ($data->$vard) echo $data->$vard; ?>"> <br style="margin-bottom:10px;"><span class="example">Example: 偶然に</span></td>
			<td>Image <input type="file" name="varimage[]" class="file_1" /> <?php  if (!empty($data->$vard)) echo '<div style="float:right;margin-left:100px;"><a href="'.printimage('/datas/quiz/image/'.$id.'_'.$i).'" target="_blank"><img src="'.printimage('/datas/quiz/image/'.$id.'_'.$i).'?'.time().'" height="60" /></a></div>'; ?></td>
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
	
