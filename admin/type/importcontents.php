<?php

include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$act         = optional_param('act', NULL, PARAM_TEXT);
$id          = optional_param('id', NULL, PARAM_INT);

if (!$options['serverlogin'] || !$options['serverpassword']) 
  die("You need account on our server before. If you already have it, please add it on options page: <a href='index.php?type=options'>options</a>");


$postdata = http_build_query(array('serverlogin' => $options['serverlogin'],'serverpassword' => $options['serverpassword'], 'siteurl' => $CFG['wwwroot']));
$opts = array('http' => array(
  'method'  => 'POST',
  'header'  => 'Content-type: application/x-www-form-urlencoded',
  'content' => $postdata
 ));
$context  = stream_context_create($opts);
$result = @file_get_contents($mcm_server.'/apps/index.php', false, $context);

?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#list-table').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
    });
});
    
function installrow(link, row) {
    if(confirm('Install?')) {
        $('#'+row).remove();
        $.post(link);
        return false;
    }else {
        return false;
    }
    
}
</script>




	<!--  start page-heading -->
	<div id="page-heading">
		<h1>List of avaiable applications activity</h1>
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
		
		<form id="formadd" name="formadd_point" method="post" action="?type=importcontents_ajax">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="list-table"> 
<thead> 
<tr>
    <th width="20"></th> 
    <th width="150">Title</th> 
    <th width="150">Category</th> 
    <th width="80">Type</th> 
    <th width="80">Language</th> 
    <th width="150">ID</th> 
    <th width="250">Description</th> 
    <th width="50">Price</th> 
    <th width="50">Items</th> 
    <th width="50">Date</th> 
    <th width="50">Install</th> 
</tr>
</thead> 
<tbody> 
<?php

$data = json_decode($result);

$c = 0;

while(list($key,$value)=each($data)) {
  if (!get_record($appstables[$value->type], array("category" => $value->category))) {
    if (empty($value->price)) $value->price = "free";
$c++;
  echo '<tr id="item-'.$c.'"> 
    <td><input type="checkbox" name="activity['.$value->id.']" value="1" /></td>
    <td>'.$value->title.'</td>
    <td>'.$value->category.'</td>
    <td>'.$appsnewnames[$value->type].'</td>
    <td>'.$value->lang.'</td>
    <td>'.$value->id.'</td>
    <td>'.$value->description.'</td>
    <td>'.$value->price.'</td>
    <td>'.$value->countofitems.'</td>
    <td>'.str_replace(" ", "&nbsp;", date("d M Y", $value->date)).'</td>
    <td><a href="?type=importcontents_ajax&activity['.$value->id.']=1" onclick="if(confirm(\'Install?\')) return true; else return false;">install</a></td> 
  </tr>';
  }
}

?>
</tbody><tfoot>
<tr>
    <th width="20"></th> 
    <th width="150">Title</th> 
    <th width="150">Category</th> 
    <th width="80">Type</th> 
    <th width="80">Language</th> 
    <th width="150">ID</th> 
    <th width="250">Description</th> 
    <th width="50">Price</th> 
    <th width="50">Items</th> 
    <th width="50">Date</th> 
    <th width="50">Install</th> 
</tr>
</tfoot>
</table>
        <div style="padding:30px;"><input type="submit" value="" class="form-submit" /></div>
        </form>


		 
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
	
