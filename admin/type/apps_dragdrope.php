<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act        = optional_param('act', NULL, PARAM_TEXT);
$category   = optional_param('category', NULL, PARAM_TEXT);
$var        = optional_param('var', NULL, PARAM_TEXT);
$a          = optional_param('a', NULL, PARAM_TEXT);
$id         = optional_param('id', NULL, PARAM_INT);


if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}apps_dragdrope WHERE id = ".$id);
    

if ($category) {
    $datam              = array();
    $datam['category']  = $category;
     
    while(list($key,$value)=each($var)) {
      $datam['var'.($key + 1)]  = $value;
    }
    reset($var);
    
    while(list($key,$value)=each($a)) {
      $datam['a'.($key + 1)]  = $value;
    }
    reset($a);
     
    if ($id) {
        $datam['id'] = $id;
    }
    
    $id = insert_record("apps_dragdrope", $datam);
    
    //print_r ($_FILES);
    
    for($key=0;$key<5;$key++){
      //echo "1:".$_FILES['imga']['tmp_name'][$key]."<br />";
      //echo "2:".$_FILES['imgvar']['tmp_name'][$key]."<br />";
      //echo "3:".$_FILES['soundtextvar']['tmp_name'][$key]."<br />";
      //echo "4:".$_FILES['soundtexta']['tmp_name'][$key]."<br />";
    
      if ($_FILES['imgvar']['tmp_name'][$key]) {
        list($width, $height, $type, $attr) = getimagesize($_FILES['imgvar']['tmp_name'][$key]);
        if ($width > 180 || $height > 180) {
            $image = new SimpleImage();
            $image->load($_FILES['imgvar']['tmp_name'][$key]);
            if ($width > $height) {
                $image->resizeToWidth(180);
            }
            else
            {
                $image->resizeToHeight(180);
            }
            $image->save($CFG['dirroot']."/datas/dragdrope/imgvar/{$id}_".($key + 1).".jpg");
        }
        else
        {
            move_uploaded_file ($_FILES['varimage']['tmp_name'][$key], $CFG['dirroot']."/datas/dragdrope/imgvar/{$id}_".($key + 1).".jpg");
        }
      }
      
      if ($_FILES['soundtextvar']['tmp_name'][$key]) 
        move_uploaded_file ($_FILES['soundtextvar']['tmp_name'][$key], $CFG['dirroot']."/datas/dragdrope/soundtextvar/{$id}_".($key + 1).".mp3");
        

      if ($_FILES['imga']['tmp_name'][$key]) {
        //echo "!<Br />";
        list($width, $height, $type, $attr) = getimagesize($_FILES['imga']['tmp_name'][$key]);
        //echo "{$width}, {$height}, {$type}, {$attr}<br />";
        if ($width > 180 || $height > 180) {
            $image = new SimpleImage();
            $image->load($_FILES['imga']['tmp_name'][$key]);
            if ($width > $height) {
                $image->resizeToWidth(180);
            }
            else
            {
                $image->resizeToHeight(180);
            }
            $image->save($CFG['dirroot']."/datas/dragdrope/imga/{$id}_".($key + 1).".jpg");
        }
        else
        {
            move_uploaded_file ($_FILES['imga']['tmp_name'][$key], $CFG['dirroot']."/datas/dragdrope/imga/{$id}_".($key + 1).".jpg");
        }
      }
      
      if ($_FILES['soundtexta']['tmp_name'][$key]) 
        move_uploaded_file ($_FILES['soundtexta']['tmp_name'][$key], $CFG['dirroot']."/datas/dragdrope/soundtexta/{$id}_".($key + 1).".mp3");
        
        
      if(is_file($CFG['dirroot']."/datas/dragdrope/soundtexta/{$id}_".($key + 1).".mp3")) 
        $datam['soundtexta'.($key + 1)] = 1;
      else
        $datam['soundtexta'.($key + 1)] = 0;
        
      if(is_file($CFG['dirroot']."/datas/dragdrope/soundtextvar/{$id}_".($key + 1).".mp3")) 
        $datam['soundtextvar'.($key + 1)] = 1;
      else
        $datam['soundtextvar'.($key + 1)] = 0;
        
      if(is_file($CFG['dirroot']."/datas/dragdrope/imgvar/{$id}_".($key + 1).".jpg")) 
        $datam['imgvar'.($key + 1)] = 1;
      else
        $datam['imgvar'.($key + 1)] = 0;
        
      if(is_file($CFG['dirroot']."/datas/dragdrope/imga/{$id}_".($key + 1).".jpg")) 
        $datam['imga'.($key + 1)] = 1;
      else
        $datam['imga'.($key + 1)] = 0;
    }
    
    
    $datam['id'] = $id;
    insert_record("apps_dragdrope", $datam);
    
    //if ($_FILES['soundword']['tmp_name']) move_uploaded_file ($_FILES['soundword']['tmp_name'], $CFG['dirroot']."/datas/dragdrope/soundword/{$id}.mp3");
    
    $status = statusmessage('Added');
    
}

if ($act == "edit" && !empty($id))
    $data = get_record("apps_dragdrope", array("id"=>$id));
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> dragdrope item</h1>
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
			<div class="step-dark-left"><a href="#">Add dragdrope item details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="http://webapp.netcourse.org/products.php#dragdrope" target="_blank">Fields help</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=apps_dragdrope" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Category:</th>
			<td><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: UNIT 1</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_dragdrope&",
		varname:"input",
		json:true
	};
	var as_json = new AutoSuggest('catsuggest', options);
			</script>
			</div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
	
<?php
        for ($i=1;$i<=5;$i++) {
          $var          = "var".$i;
          $imgvar       = "imgvar".$i;
          $soundtextvar = "soundtextvar".$i;
        ?>
		<tr>
			<th valign="top">Question <?php echo $i; ?>:</th>
			<td><input type="text" style="size:200px;" name="var[]" class="inp-form" value="<?php if ($data->$vard) echo $data->$vard; ?>"> <br style="margin-bottom:10px;"><span class="example"></span></td>
			<td>Image <input type="file" name="imgvar[]" class="file_1" /> <?php  if (!empty($data->$vard)) echo '<div style="float:right;margin-left:100px;"><a href="'.printimage('/datas/dragdrope/imgvar/'.$id.'_'.$i).'" target="_blank"><img src="'.printimage('/datas/dragdrope/imgvar/'.$id.'_'.$i).'?'.time().'" height="60" /></a></div>'; ?></td>
		</tr>
		<tr>
		  <td></td>
			<td width="350">Audio: <input type="file" name="soundtextvar[]" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->{$soundtextvar})) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/dragdrope/soundtextvar/'.$id.'_'.$i.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>

			</td>
		</tr>
        <?php
        
        }
?>



<?php
        for ($i=1;$i<=5;$i++) {
          $var          = "a".$i;
          $imgvar       = "imga".$i;
          $soundtexta   = "soundtexta".$i;
        ?>
		<tr>
			<th valign="top">Answer <?php echo $i; ?>:</th>
			<td><input type="text" style="size:200px;" name="a[]" class="inp-form" value="<?php if ($data->$vard) echo $data->$vard; ?>"> <br style="margin-bottom:10px;"><span class="example"></span></td>
			<td>Image <input type="file" name="imga[]" class="file_1" /> <?php  if (!empty($data->$vard)) echo '<div style="float:right;margin-left:100px;"><a href="'.printimage('/datas/dragdrope/imga/'.$id.'_'.$i).'" target="_blank"><img src="'.printimage('/datas/dragdrope/imga/'.$id.'_'.$i).'?'.time().'" height="60" /></a></div>'; ?></td>
		</tr>
		<tr>
		  <td></td>
			<td width="350">Audio: <input type="file" name="soundtexta[]" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->{$soundtexta})) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/dragdrope/soundtexta/'.$id.'_'.$i.'.mp3" target="_blank">audio</a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">MP3 1MB max per audio</div>
			<div class="bubble-right"></div>
			<?php } ?>

			</td>
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
	
