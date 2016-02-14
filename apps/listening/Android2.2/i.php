<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script>
var playmark = 0;
var page = 1;
$(document).ready(function(){
  
  $("#playimage").attr("src","<?php echo $CFG['wwwroot']; ?>/images/pause.png");
  
  $('#sub1').click(function() {
    $('#studentfeedback').html($('#text').val());
    window.page = 2;
    $("#playimage2").attr("src","<?php echo $CFG['wwwroot']; ?>/images/pause.png");
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
    if (window.playmark == 0) {
      niftyplayer('audiotext').play();
      window.playmark = 1;
      $("#playimage").attr("src","<?php echo $CFG['wwwroot']; ?>/images/pause.png");
    }
    return false;
  });

  $('#playbutton2').click(function() {
    if (window.playmark == 0) {
      niftyplayer('audiotext2').play();
      window.playmark = 1;
      $("#playimage2").attr("src","<?php echo $CFG['wwwroot']; ?>/images/pause.png");
    }
    return false;
  });
  
});

setInterval("playerstatuscheck()",200);
  
  function playerstatuscheck() {
   if (window.page == 1) {
    if (niftyplayer('audiotext').getState() == "empty") {
      niftyplayer('audiotext').play();
    } else if (niftyplayer('audiotext').getState() == "playing") {
      if (window.playmark == 0) {
        window.playmark = 1;
      }
    } else if (niftyplayer('audiotext').getState() == "finished") {
      if (window.playmark == 1) {
        $("#playimage").attr("src","<?php echo $CFG['wwwroot']; ?>/images/play.png");
        window.playmark = 0; 
      }
    }
   } else if (window.page == 2) {
    if (niftyplayer('audiotext2').getState() == "empty") {
      niftyplayer('audiotext2').play();
    } else if (niftyplayer('audiotext2').getState() == "playing") {
      if (window.playmark == 0) {
        window.playmark = 1;
      }
    } else if (niftyplayer('audiotext2').getState() == "finished") {
      if (window.playmark == 1) {
        $("#playimage2").attr("src","<?php echo $CFG['wwwroot']; ?>/images/play.png");
        window.playmark = 0; 
      }
    }
   }
  }
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
	
	
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/listening/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/listening/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
		
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
	
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext2" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/listening/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/listening/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext2" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
	
</div>

</body>
</html>