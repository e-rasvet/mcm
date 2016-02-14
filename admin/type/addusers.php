<?php

//include_once "../../config.php";
include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act        = optional_param('act', NULL, PARAM_TEXT);
$username   = optional_param('username', NULL, PARAM_SAFEDIR);
$password   = optional_param('password', NULL, PARAM_TEXT);
$firstname  = optional_param('firstname', NULL, PARAM_TEXT);
$lastname   = optional_param('lastname', NULL, PARAM_TEXT);
$email      = optional_param('email');
$id         = optional_param('id', NULL, PARAM_INT);

if ($act == "delete" && !empty($id))
    $db->query("DELETE FROM {$CFG['prefix']}user WHERE id = ".$id);

if ($username) {
    $datam              = array();
    $datam['username']  = $username;
    if (isset($password))
      $datam['password']  = md5($password);
    $datam['firstname'] = $firstname;
    $datam['lastname']  = $lastname;
    $datam['email']     = $email;
     
    if ($id) 
        $datam['id'] = $id;
    
    if (get_record("user", array("username" => $username)) && !$id) 
      $status = statusmessage($username.' - Username exist try other one','red');
    else if (get_record("user", array("email" => $email)) && !$id) 
      $status = statusmessage($email.' - Email exist try other one','red');
    else {
      $id = insert_record("user", $datam);
      $status = statusmessage($username.' - Added successfully');
    }
}

if ($act == "edit" && !empty($id))
    $data = get_record("user", array("id"=>$id));
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
		<h1><?php if ($data->id) echo "Edit"; else echo "Add"; ?> user</h1>
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
			<div class="step-dark-left"><a href="#">Add user details</a></div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=addusers<?php if ($id) echo "&act=edit&id=".$id; ?>" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Username:</th>
			<td><input type="text" name="username" class="inp-form-error" value="<?php if (isset($data->username)) echo $data->username; ?>" /></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Password:</th>
			<td><input type="text" name="password" class="inp-form" /></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Firstname:</th>
			<td><input type="text" name="firstname" class="inp-form" value="<?php if (isset($data->firstname)) echo $data->firstname; ?>" /></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Lastname:</th>
			<td><input type="text" name="lastname" class="inp-form" value="<?php if (isset($data->lastname)) echo $data->lastname; ?>" /></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Email:</th>
			<td><input type="text" name="email" class="inp-form-error" value="<?php if (isset($data->email)) echo $data->email; ?>" /></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
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
	
	


