<?php

include_once "../config.php";

$file           = optional_param('file', NULL, PARAM_TEXT);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="mcm, Help " />
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG['wwwroot']; ?>/css/helpstyle.css" />
    <title>Help</title>
</head>

<body  class=" course-1 dir-ltr lang-en_utf8" id="help">

<div id="page">

    <div id="content" class=" clearfix"><div class="box generalbox generalboxcontent">
    
    <?php
    
    if (is_file($CFG['dirroot']."/help/{$file}.html")) {
      include_once $CFG['dirroot']."/help/{$file}.html";
    } else {
      echo '<h1>No file</h1>';
    }
    ?>
    
    </div>
    <div class="closewindow">
<form action="#"><div><input type="button" onclick="self.close();" value="Close this window" /></div></form></div>
    </div>
    <div id="footer"><hr /><p class="helplink"></p></div>

</div>
</body>
</html>


