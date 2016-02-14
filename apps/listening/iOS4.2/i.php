<script>
$(document).ready(function(){
  var v = document.getElementById("audiotext");
  var playmark = 0;
  
  v.load();
  
  v.addEventListener("ended", function() { 
    $("#playimage").attr("src","<?php echo $CFG['wwwroot']; ?>/images/play.png");
    $("#playimage2").attr("src","<?php echo $CFG['wwwroot']; ?>/images/play.png");
    playmark = 0; 
  }, true);
  
  $('#sub1').click(function() {
    $('#studentfeedback').html($('#text').val());
  });
  
  $('#sub2').click(function() {
    $.post('<?php echo $CFG['wwwroot']; ?>/stat.php', { apps: "listening", id: <?php echo $data->id; ?>, text: $('#text').val });
    window.setTimeout("window.location ='<?php
if (!empty($idnext)) {
  echo $CFG['wwwroot'].'/apps/listening/index.php?id='.$idnext;
} else {
  echo $CFG['wwwroot'] .'/index.php/c/'.$course->id;
}
?>'", 500);
    return false;
  });
  
  $('#playbutton').click(function() {
    if (playmark == 1) {
      v.pause();
      playmark = 0;
      $("#playimage").attr("src","<?php echo $CFG['wwwroot']; ?>/images/play.png");
    }
    else
    {
      v.play();
      playmark = 1;
      $("#playimage").attr("src","<?php echo $CFG['wwwroot']; ?>/images/pause.png");
    }
    return false;
  });
  
  
  
  $('#playbutton2').click(function() {
    if (playmark == 1) {
      v.pause();
      playmark = 0;
      $("#playimage2").attr("src","<?php echo $CFG['wwwroot']; ?>/images/play.png");
    }
    else
    {
      v.play();
      playmark = 1;
      $("#playimage2").attr("src","<?php echo $CFG['wwwroot']; ?>/images/pause.png");
    }
    return false;
  });
  
});
</script>
</head>
<body>

<h1 id="pageTitle">Listen</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>


<div id="home" title="listen" selected="true" style="width:450px;padding-top:10px;padding-left:10px;">
	<div style="float:left;width:48px;">
		<div><a href="#" id="playbutton" target="_webapp"><img src="<?php echo $CFG['wwwroot']; ?>/images/play.png" width="32" height="32" alt="play" id="playimage" /></a></div>

		<div><a href="#nextpage" id="sub1"><img src="<?php echo $CFG['wwwroot']; ?>/images/submit.png" width="32" height="32" alt="submit" id="submitimage" /></a></div>
	</div>
	
	<div style="width:390px;float:right;"><textarea name="text" id="text" style="width:390px;height:100px;"></textarea></div>
	
</div>
<div id="nextpage" title="Vocabulary" style="width:450px;padding-top:10px;padding-left:10px;">

	<div style="float:left;width:48px;">
		<div><a href="#" id="playbutton2" target="_webapp"><img src="<?php echo $CFG['wwwroot']; ?>/images/play.png" width="32" height="32" alt="play" id="playimage2" /></a></div>

		<div><a href="#" id="sub2"><img src="<?php echo $CFG['wwwroot']; ?>/images/submit.png" width="32" height="32" alt="submit" id="submitimage" /></a></div>
	</div>

	<div style="width:390px;float:right;">
		<div style="text-align: center; padding-top: 1px; padding-bottom: 1px; width: 390px; margin: 0pt auto;text-align:left;border: 1px solid;"><div style="padding:3px;"><?php echo $data->feedback; ?></div></div>

		<div style="margin:2px;"></div>

		<div style="text-align: center; padding-top: 1px; padding-bottom: 1px; width: 390px; margin: 0pt auto;text-align:left;border: 1px solid;"><div style="padding:3px;" id="studentfeedback"></div></div>
	</div>
	
</div>


<div><audio src="<?php echo $CFG['wwwroot']; ?>/datas/listening/soundtext/<?php echo $data->id; ?>.mp3" id="audiotext" autobuffer="autobuffer"></audio></div>

</body>
</html>