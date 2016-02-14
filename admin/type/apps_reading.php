<?php

include_once $CFG['dirroot'] . "/inc/SimpleImage.php";


$act              = optional_param('act', NULL, PARAM_TEXT);
$title            = optional_param('title', NULL, PARAM_TEXT);
$text             = optional_param('text', NULL, PARAM_TEXT);
$category         = optional_param('category', NULL, PARAM_TEXT);
$varvocab         = optional_param('varvocab', NULL, PARAM_TEXT);
$vartranslation   = optional_param('vartranslation', NULL, PARAM_TEXT);
$actype           = optional_param('actype', NULL, PARAM_TEXT);
$vartiming        = optional_param('vartiming', NULL, PARAM_NUMBER);
$id               = optional_param('id', NULL, PARAM_INT);
$ids              = optional_param('ids', NULL, PARAM_INT);


if ($act == "deleteitem" && !empty($ids))
    $db->query("DELETE FROM {$CFG['prefix']}apps_reading_texts WHERE id = ".$ids);

if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}apps_reading WHERE id = ".$id);


if ($title) {
  if(!empty($actype)) {
    $videotype = 0;
    
    if ($_FILES['video']['tmp_name'] || is_file($CFG['dirroot'].'/datas/reading/video/'.$id.'.m4v')) $videotype = 1;
    
    $datam              = array();
    $datam['title']     = $title;
    $datam['soundtext'] = $soundtexttype;
    $datam['category']  = $category;
    
    if ($id) {
        $datam['id'] = $id;
        //$db->query("DELETE FROM {$CFG['prefix']}apps_reading_texts WHERE readerid = ".$id);
    }
    
    if (!empty($videotype)) 
      $datam['video']  = 1;
    
    $id = insert_record("apps_reading", $datam);
    
    
    $status = statusmessage($title.' - Content added - '. ' <a href="index.php?type=apps_reading&act=edit&id='.$id.'">[Edit content]</a> <a href="#" onclick="$(\'#formadd\').show();return false;">[Add new content]</a>');
    
    if (!empty($_FILES['video']['tmp_name']))
      move_uploaded_file ($_FILES['video']['tmp_name'], $CFG['dirroot'].'/datas/reading/video/'.$id.'.m4v');
    
    
    unset($id);
  } else {

    $soundwordtype = 0;
    
    if ($_FILES['soundtext']['tmp_name'] || is_file($CFG['dirroot'].'/datas/reading/soundtext/'.$id.'.mp3')) $soundtexttype = 1;
    
    
    $datam              = array();
    $datam['title']     = $title;
    $datam['soundtext'] = $soundtexttype;
    $datam['category']  = $category;
     
    if ($id) {
        $datam['id'] = $id;
        //$db->query("DELETE FROM {$CFG['prefix']}apps_reading_texts WHERE readerid = ".$id);
    }
    
    $id = insert_record("apps_reading", $datam);

    if ($_FILES['image']['tmp_name']) {
      list($width, $height, $type, $attr) = getimagesize($_FILES['image']['tmp_name']);
      if ($width > 320 || $height > 250) {
          $image = new SimpleImage();
          $image->load($_FILES['image']['tmp_name']);
          if ($width > $height) {
            $image->resizeToWidth(320);
          }
          else
          {
            $image->resizeToHeight(250);
          }
          $image->save($CFG['dirroot']."/datas/reading/image/{$id}.".get_image_type($_FILES['image']['tmp_name']));
      }
      else
      {
          move_uploaded_file ($_FILES['image']['tmp_name'], $CFG['dirroot']."/datas/reading/image/{$id}.".get_image_type($_FILES['image']['tmp_name']));
      }
    }
    
    
    while(list($key,$value)=each($text)) {
      if (!empty($value)) {
          $datamt              = array();
          $datamt['readerid']  = $id;
          $datamt['text']      = $value;
          $datamt['timing']    = $vartiming[$key];
          
          if (isset($varvocab[$key])) $datamt['vocabulary']         = $varvocab[$key];
          if (isset($vartranslation[$key])) $datamt['translation']  = $vartranslation[$key];
          
          if ($_FILES['varimage']['tmp_name'][$key]) $datamt['image'] = 1;
          
          if ($datamtold = get_record("apps_reading_texts",array("readerid" => $id, "timing" => $datamt['timing']))) {
            $datamt['id'] = $datamtold->id;
          }
          
          $idt = insert_record("apps_reading_texts", $datamt);
          
          if ($_FILES['varimage']['tmp_name'][$key]) {
            list($width, $height, $type, $attr) = getimagesize($_FILES['varimage']['tmp_name'][$key]);
            if ($width > 320 || $height > 250) {
                $image = new SimpleImage();
                $image->load($_FILES['varimage']['tmp_name'][$key]);
                if ($width > $height) {
                    $image->resizeToWidth(320);
                }
                else
                {
                    $image->resizeToHeight(250);
                }
                $image->save($CFG['dirroot']."/datas/reading/image/{$id}_{$idt}.".get_image_type($_FILES['varimage']['tmp_name'][$key]));
            }
            else
            {
                move_uploaded_file ($_FILES['varimage']['tmp_name'][$key], $CFG['dirroot']."/datas/reading/image/{$id}_{$idt}.".get_image_type($_FILES['varimage']['tmp_name'][$key]));
            }
          }
      }
    }
    
    if ($_FILES['soundtext']['tmp_name']) move_uploaded_file ($_FILES['soundtext']['tmp_name'], $CFG['dirroot']."/datas/reading/soundtext/{$id}.mp3");
    
    $status = statusmessage($title.' - Content added - '. ' <a href="index.php?type=apps_reading&act=edit&id='.$id.'">[Edit content]</a> <a href="#" onclick="$(\'#formadd\').show();return false;">[Add new content]</a>');
    
    unset($id);
  }
}

if ($act == "edit" && !empty($id)) {
    $data = get_record("apps_reading", array("id"=>$id));
    if (!empty($data->video)) 
      $actype = $data->video;
}
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> reading item</h1>
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
			<div class="step-dark-left"><a href="#">Add reading item details</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="http://webapp.netcourse.org/products.php#reading" target="_blank">Fields help</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no-off"></div>
			<div class="step-light-left"><a href="?type=apps_reading&actype=video">ADD VIDEO</a></div>
			<div class="step-light-right">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=apps_reading<?php if(!empty($actype)) echo "&actype=video"; ?>" onsubmit="return validate_form(this)" <?php if(isset($status)) echo 'style="display:none"'; ?>>
		<?php
		if (empty($actype)) {
		?>
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Title:</th>
			<td><input type="text" name="title" class="inp-form-error" value="<?php if (isset($data->title)) echo $data->title; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: ASIMO</span></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Category:</th>
			<td><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: Robots</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_reading&",
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
			<?php  if (!empty($data->soundtext)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/reading/soundtext/'.$id.'.mp3" target="_blank">audio</a></div>'; else {?>
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
			<?php  if (!empty($id)) echo '<div style="margin-left:20px;"><a href="'.printimage('/datas/reading/image/'.$id).'" target="_blank"><img src="'.printimage('/datas/reading/image/'.$id).'?'.time().'" height="60" /></a></div>'; else {?>
			<div class="bubble-left"></div>
			<div class="bubble-inner">JPEG, PNG 100Kb max per image (<a href="<?php echo $CFG['wwwroot']."/images/image-example.jpg"; ?>" target="_blank">Example</a>)</div>
			<div class="bubble-right"></div>
			<?php } ?>
			</td>
		
		</tr>
		
        <?php
        $bg = '#eee';
        if ($act == "edit" && !empty($id)) {
          $datat = get_records("apps_reading_texts", array("readerid"=>$id), "id");
          $countoffields = count($datat)+5;
        } else
          $countoffields = 15;

        for ($i=0;$i<=$countoffields;$i++) {
        
        ?>
        <tr style="background:<?php echo $bg; ?>;" id="tr-<?php echo $i; ?>"><td><a href="#" onclick="document.getElementById('tr-<?php echo $i; ?>').innerHTML = '';$.get('index.php',{type: 'apps_reading', ids:<?php echo $datat[$i]->id; ?>, act:'deleteitem'});return false;">[x]</a> Frame <?php echo $i; ?></td><td colspan="2">
          <div style="float:left;padding-right:10px;"><textarea name="text[]" style="width:350px;height:70px;"><?php if (isset($datat[$i]->text)) echo $datat[$i]->text; ?></textarea>
            <br style="margin-bottom:10px;"><span class="example">Example: ASIMO is a lightweight humanoid robot built by Honda Motor Co.</span>
          </div><div style="float:left;"><div> Image: <input type="file" name="varimage[]"></div>
          <?php 
            if ($datat[$i]->image == 1) 
              echo '<div style="margin-left:200px;"><a href="'.printimage('/datas/reading/image/'.$id.'_'.$datat[$i]->id).'" target="_blank"><img src="'.printimage('/datas/reading/image/'.$id.'_'.$datat[$i]->id).'?'.time().'" height="60" /></a></div>';
            else if ($id)
              echo '<div style="margin-left:200px;"><a href="'.printimage('/datas/reading/image/'.$id).'" target="_blank"><img src="'.printimage('/datas/reading/image/'.$id).'?'.time().'" height="60" /></a></div>';
            
          ?>
            </div>
          <div style="clear:both;"></div>
          <div><?php echo help_link('Timing', 'apps_reading_timing'); ?> Timing: <input type="text" style="width:50px;" name="vartiming[]" value="<?php if (isset($datat[$i]->timing)) echo $datat[$i]->timing; ?>" /> Vocabulary: <input type="text" style="width:100px;" name="varvocab[]" value="<?php if (isset($datat[$i]->vocabulary)) echo $datat[$i]->vocabulary; ?>" /> Translation: <input type="text" style="width:100px;" name="vartranslation[]" value="<?php if (isset($datat[$i]->translation)) echo $datat[$i]->translation; ?>" /></div>
          <div><span class="example" style="float:left;margin-left:20px;">Example timing: 1.0</span> <span class="example" style="float:left;margin-left:20px;">Example vocabulary: accept</span> <span class="example" style="float:left;margin-left:20px;">Example translation: ～を受け入れる</span></div>
        </td></tr>
        <?php
          if ($bg == '#eee') $bg = '#fff'; else $bg = '#eee';
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
	
	<?php } else { ?>
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Title:</th>
			<td><input type="text" name="title" class="inp-form-error" value="<?php if (isset($data->title)) echo $data->title; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: ASIMO</span></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Category:</th>
			<td><input type="text" name="category" id="catsuggest" class="inp-form-error" value="<?php if (isset($data->category)) echo $data->category; ?>" /> <br style="margin-bottom:10px;"><span class="example">Example: Robots</span></td>
			<td>
			<div class="error-left">
			<script type="text/javascript">
	var options = {
		script:"<?php echo $CFG['wwwroot']; ?>/admin/type/category_ajax.php?t=apps_reading&",
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
			<th>Video:</th>
			<td><input type="file" name="video" class="file_1" /></td>
			<td>
			<?php  if (!empty($data->video)) echo '<div style="margin-left:20px;"><a href="'.$CFG['wwwroot'].'/datas/reading/video/'.$id.'.m4v" target="_blank">video</a></div>'; else {?>
			<div class="bubble-left" style="margin-left:100px;"></div>
			<div class="bubble-inner">M4V 3MB max per video</div>
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
	<?php } ?>
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
	
