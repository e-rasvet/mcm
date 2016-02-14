<?php

include_once $CFG['dirroot'] . "/inc/SimpleImage.php";

$activity         = optional_param('activity', NULL, PARAM_TEXT);
$id               = optional_param('id', NULL, PARAM_INT);

$c = 0;
while (list($key,$value)=each($activity)) {
    $c++;
    $postdata = http_build_query(array('serverlogin' => $options['serverlogin'],'serverpassword' => $options['serverpassword'], 'siteurl' => $CFG['wwwroot'], 'unicalid' => $key));
    $opts = array('http' => array(
      'method'  => 'POST',
      'header'  => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postdata
     ));
    $context  = stream_context_create($opts);
    $result = @file_get_contents($mcm_server.'/apps/details.php', false, $context);

    $data = json_decode($result);

    $totalitems[$c]['id']     = $data->ids;
    $totalitems[$c]['type']   = $data->type;
}



?>

<script type="text/javascript">
  var quizzes = new Array();
  var quizzestype = new Array();
  <?php 
  while(list($key,$value)=each($totalitems)) {
      echo ' quizzes['.$key.'] = ['.$value['id'].'];
';
      echo ' quizzestype['.$key.'] = "'.$value['type'].'";
';
  }
  ?>

  $(document).ready(function() {
    for(i = 1; i <= <?php echo count($totalitems); ?>; i++) { 
      for(j = 0; j < quizzes[i].length; j++) {
        if (j == (quizzes[i].length - 1) && i == <?php echo count($totalitems); ?>) {
          $.post('<?php echo $CFG['wwwroot']; ?>/admin/type/importcontents_ajax_m.php', { id: quizzes[i][j], app: quizzestype[i], end: 1 }, function(data) {
            $('#installationlog').append(data);
          });
        } else {
          $.post('<?php echo $CFG['wwwroot']; ?>/admin/type/importcontents_ajax_m.php', { id: quizzes[i][j], app: quizzestype[i] }, function(data) {
            $('#installationlog').append(data);
          });
        }
      }
    }
    
  });
</script>


    <!--  start page-heading -->
    <div id="page-heading">
        <h1>Installation application content</h1>
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
        
            <div id="installationlog"></div>
<?php //print_r ($totalitems); ?>
         
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
    