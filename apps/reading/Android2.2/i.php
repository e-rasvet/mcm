<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script type="application/x-javascript">
<?php 
if (empty($data->video)) {
?>
var playmark = 0;
$(document).ready(function(){
  var hidemark = 0;
  var page = 1;

  $('#playbutton').text("Playing");
  
  $('#playbutton').click(function() {
    if (window.playmark == 0) {
      niftyplayer('audiotext').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
      audioprocess();
    }
    return false;
  });
  
  
  
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
  
  setInterval("playerstatuscheck()",200);
  
  function playerstatuscheck() {
    if (niftyplayer('audiotext').getState() == "empty") {
      niftyplayer('audiotext').play();
    } else if (niftyplayer('audiotext').getState() == "playing") {
      if (window.playmark == 0) {
      //console.log("Starting!!");
        audioprocess();
        window.playmark = 1;
      }
    } else if (niftyplayer('audiotext').getState() == "finished") {
      if (window.playmark == 1) {
      //console.log("Finished!!");
        $('#playbutton').text('Play'); 
        window.playmark = 0; 
        $('#faceimg').attr('src','<?php echo printimage('/datas/reading/image/'.$data->id); ?>'); 
        $('#shownextbutton').toggle();
      }
    }
  }
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

<?php if(!empty($data->soundtext)) { ?>
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/reading/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/reading/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
<?php } ?>


</div>

<div id="nextpage" title="Vocabulary">

</div>


<?php
} else {
?>
<script src="<?php echo $CFG['wwwroot']; ?>/js/mediaelement-and-player.min.js"></script>
<link rel="stylesheet" href="<?php echo $CFG['wwwroot']; ?>/css/mediaelementplayer.min.css" />
<div id="home" title="Reading" selected="true">
<video src="<?php echo $CFG['wwwroot']."/datas/reading/video/".$data->id.".m4v" ?>" controls="controls" id="faceimg" width="640" height="480">your browser does not support the video tag</video>

<div style="text-align:center;margin-top:30px;" id="shownextbutton"><a class="whiteButton" type="button" href="<?php
if (!empty($idnext)) {
  echo $CFG['wwwroot'].'/apps/reading/index.php?id='.$idnext;
} else {
  echo $CFG['wwwroot'] .'/index.php/c/'.$course->id;
}
?>" target="_webapp"><?php if (!empty($idnext)) echo 'Next ->'; else echo 'Back'; ?></a></div>

</div>
<script>
$('video').mediaelementplayer();
</script>
<?php
}
?>

</body>
</html>