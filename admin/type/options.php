<?php

$serverlogin        = optional_param('serverlogin', NULL, PARAM_TEXT);
$serverpassword     = optional_param('serverpassword', NULL, PARAM_TEXT);
$id                 = optional_param('id', NULL, PARAM_INT);

/*
if (!empty($serverlogin)) {
  $data = get_record("options", array("name" => "serverlogin"));
  $datam              = array();
  $datam['value']     = $serverlogin;
  
  if (!isset($data->id))
    $datam['name']     = 'serverlogin';
  
  $datam['id']        = $data->id;
  $id = insert_record("options", $datam);
  
  $status = "Settings Was updated";
}

if (!empty($serverpassword)) {
  $data = get_record("options", array("name" => "serverpassword"));
  $datam              = array();
  $datam['value']     = $serverpassword;
  
  if (!isset($data->id))
    $datam['name']     = 'serverpassword';
  
  $datam['id']        = $data->id;
  $id = insert_record("options", $datam);
  
  $status = "Settings Was updated";
}
*/

if ($frm = data_submitted()) {
  foreach ($frm as $k => $v){
      $datam              = array();
      $datam['value']     = $v;
      $datam['name']      = $k;
      
      if ($data = get_record("options", array("name" => $k)))
        $datam['id']        = $data->id;
      
      $id = insert_record("options", $datam);
  }
  
  $status = "Settings Was updated";
  
  $optionsarray = get_records("options");
  $options = array();
  while(list($key,$value)=each($optionsarray)) {
    $options[$value->name] = $value->value;
  }
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
		<h1>Options</h1>
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
			<div class="step-dark-left"><a href="#">Options</a></div>
			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		
		<?php if(isset($status)) echo $status; ?>

		<!-- start id-form -->
		<form id="formadd" name="formadd_point" enctype="multipart/form-data" method="post" action="?type=options" onsubmit="return validate_form(this)">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Server Login:</th>
			<td><input type="text" name="serverlogin" class="inp-form-error" value="<?php if (isset($options['serverlogin'])) echo $options['serverlogin']; ?>" /></td>
			<td>
			<div class="error-left"></div>
			<div class="error-inner">This field is required.</div>
			</td>
		</tr>
		<tr>
			<th valign="top">Server Password:</th>
			<td><input type="text" name="serverpassword" value="<?php if (isset($options['serverpassword'])) echo $options['serverpassword']; ?>" class="inp-form" /></td>
			<td>If you forgot your password, <a href="<?php echo $mcm_server; ?>/restorepassword.php" target="_blank">please click here</a></td>
		</tr>

		<tr>
			<th valign="top">Show default </th>
			<td>
			<select name="defaultentries">
			<?php
			$range = array(10,25,50,100);
			
			foreach($range as $v){
			  echo '<option value="'.$v.'"';
			  if($v == $options['defaultentries']) echo ' selected="selected" ';
			  echo '>'.$v.'</option>';
			}
			
			?>
			</select>
			</td>
			<td> entries</td>
		</tr>
		
		<tr>
			<th valign="top">Students in Quiz Game </th>
			<td>
			<select name="studentsinquizgame">
			<?php
			$range = array(2,3,4,5,6);
			
			foreach($range as $v){
			  echo '<option value="'.$v.'"';
			  if($v == $options['studentsinquizgame']) echo ' selected="selected" ';
			  echo '>'.$v.'</option>';
			}
			
			?>
			</select>
			</td>
			<td> in group</td>
		</tr>
		
		<tr>
			<th valign="top">Allow guest login </th>
			<td>
			<select name="guest_enable">
			<?php
			$range = array(1=>'Yes', 2=>'No');
			
			foreach($range as $k => $v){
			  echo '<option value="'.$k.'"';
			  if($k == $options['guest_enable']) echo ' selected="selected" ';
			  echo '>'.$v.'</option>';
			}
			
			?>
			</select>
			</td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">Use LDAP </th>
			<td>
			<select name="ldap_enable">
			<?php
			$range = array(1=>'Yes', 2=>'No');
			
			foreach($range as $k => $v){
			  echo '<option value="'.$k.'"';
			  if($k == $options['ldap_enable']) echo ' selected="selected" ';
			  echo '>'.$v.'</option>';
			}
			
			?>
			</select>
			</td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP host url:</th>
			<td><input type="text" name="ldap_host_url" value="<?php if (isset($options['ldap_host_url'])) echo $options['ldap_host_url']; ?>"  class="inp-form" /></td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP version:</th>
			<td><input type="text" name="ldap_version" value="<?php if (isset($options['ldap_version'])) echo $options['ldap_version']; ?>"  class="inp-form" /></td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP user type:</th>
			<td><input type="text" name="ldap_user_type" value="<?php if (isset($options['ldap_user_type'])) echo $options['ldap_user_type']; ?>"  class="inp-form" /></td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP bind dn:</th>
			<td><input type="text" name="ldap_bind_dn" value="<?php if (isset($options['ldap_bind_dn'])) echo $options['ldap_bind_dn']; ?>"  class="inp-form" /></td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP bind pw:</th>
			<td><input type="text" name="ldap_bind_pw" value="<?php if (isset($options['ldap_bind_pw'])) echo $options['ldap_bind_pw']; ?>"  class="inp-form" /></td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP opt deref:</th>
			<td><input type="text" name="ldap_opt_deref" value="<?php if (isset($options['ldap_opt_deref'])) echo $options['ldap_opt_deref']; ?>"  class="inp-form" /></td>
			<td></td>
		</tr>
		
		<tr>
			<th valign="top">LDAP context:</th>
			<td><input type="text" name="ldap_context" value="<?php if (isset($options['ldap_context'])) echo $options['ldap_context']; ?>"  class="inp-form" /></td>
			<td></td>
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
	
	


