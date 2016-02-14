<script type="application/x-javascript">
<?php 
if (empty($data->video)) {
?>
$(document).ready(function(){
  var v = document.getElementById("audiotext");
  var playmark = 0;
  var hidemark = 0;
  var page = 1;
  
  
  v.load();
  v.play(); $('#playbutton').text("Playing"); playmark = 1;
  
  v.addEventListener("loadedmetadata", function() { audioprocess(); }, true);
  
  v.addEventListener("ended", function() { $('#playbutton').text("Play"); playmark = 0; $("#faceimg").attr("src","<?php echo printimage('/datas/reading/image/'.$data->id); ?>"); $('#shownextbutton').toggle(); }, true);
  
  $('#playbutton').click(function() {
    if (playmark == 0) {
      v.play();
      playmark = 1;
      $('#playbutton').text("Playing");
      audioprocess();
    }
    return false;
  });
  
  
  function changetext(text) {
      $('#text-of-reader').html(text);
  }
  
  function audioprocess() {
  <?php
$texts = get_records("apps_reading_texts", array("readerid" => $data->id));
foreach ($texts as $value) {
   $value->text = str_replace($value->vocabulary, '<span style=color:red>'.$value->vocabulary.'</span>',$value->text);
   $value->timing = $value->timing * 1000;
   if (empty($value->timing)) $value->timing = 200;
   
   echo 'setTimeout(\'$("#text-of-reader").html("'.addslashes(stripslashes($value->text)).'")\', '.$value->timing.'); 
';
   echo 'setTimeout(\'$("#text-hard-word").html("'.$value->translation.'")\', '.$value->timing.'); 
';
   if (!empty($value->image)) {
     echo 'setTimeout(\'$("#faceimg").attr("src","'.printimage('/datas/reading/image/'.$data->id.'_'.$value->id).'")\', '.($value->timing - 200).'); 
';
   }
}
?>
  }
  
  $('#text-of-reader').click(function() {
    $("#text-of-reader").toggle("slow");
    $("#text-hard-word").toggle("slow");
    $("#text-of-reader2").toggle("slow");
    return false;
  });
  
  $('#text-of-reader2').click(function() {
    $("#text-of-reader").toggle("slow");
    $("#text-hard-word").toggle("slow");
    $("#text-of-reader2").toggle("slow");
    return false;
  });
  
});

<?php } ?>
</script>
</head>
<body>

<h1 id="pageTitle">Reading</h1>

<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<?php
if (empty($data->video)) {
?>

<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Reading" selected="true">
<div id="hideclick" style="text-align:center;padding-top:5px;padding-bottom:10px;"><img src="<?php echo printimage('/datas/reading/image/'.$data->id); ?>" alt="" id="faceimg" /></div>
<div style="text-align:center;padding-bottom:10px;" id="text-of-reader"></div>
<div style="text-align:center;padding-bottom:10px;display:none" id="text-of-reader2">View text</div>
<div style="text-align:center;" id="text-hard-word"></div>

<div style="text-align:center;display:none;" id="shownextbutton"><a class="whiteButton" type="button" href="<?php
if (!empty($idnext)) {
  echo $CFG['wwwroot'].'/apps/reading/index.php?id='.$idnext;
} else {
  echo $CFG['wwwroot'] .'/index.php/c/'.$course->id;
}
?>" target="_webapp"><?php if (!empty($idnext)) echo 'Next ->'; else echo 'Back'; ?></a></div>

</div>

<div id="nextpage" title="Vocabulary">
<?php if(!empty($data->soundtext)) { ?>
<audio src="<?php echo $CFG['wwwroot']."/datas/reading/soundtext/".$data->id.".mp3" ?>" id="audiotext" autobuffer="autobuffer"></audio>
<?php } ?>
</div>

<?php
} else {
?>
<div id="home" title="Reading" selected="true">
<video src="<?php echo $CFG['wwwroot']."/datas/reading/video/".$data->id.".m4v" ?>" controls="controls" id="faceimg">your browser does not support the video tag</video>

<div style="text-align:center;margin-top:30px;" id="shownextbutton"><a class="whiteButton" type="button" href="<?php
if (!empty($idnext)) {
  echo $CFG['wwwroot'].'/apps/reading/index.php?id='.$idnext;
} else {
  echo $CFG['wwwroot'] .'/index.php/c/'.$course->id;
}
?>" target="_webapp"><?php if (!empty($idnext)) echo 'Next ->'; else echo 'Back'; ?></a></div>

</div>
<?php
}
?>

</body>
</html>