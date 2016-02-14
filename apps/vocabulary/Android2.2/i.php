<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script type="application/x-javascript">
  var playmark = 0;
  var page = 1;
$(document).ready(function(){
  
  $('#playbutton').text("Playing");

  $('#playbutton').click(function() {
   if (window.page == 1) {
    if (window.playmark == 0) {
      niftyplayer('audioword').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
    }
    return false;
   } else if (window.page == 2) {
    if (window.playmark == 0) {
      niftyplayer('audiotext').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
    }
    return false;
   }
  });
  $('#definition').click(function() {
    window.page = 2;
    $('#playbutton').text("Playing");
    niftyplayer('audiotext').play();
    window.playmark = 1;
  });
  
  $('#nextpage').click(function() {
<?php
if (!empty($idnext)) {
?>
window.location = "<?php echo $CFG['wwwroot']; ?>/apps/vocabulary/index.php?id=<?php echo $idnext; ?>";
<?php
} else {
?>
window.location = "<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>";
<?php
}
?>
  });
  
});

  
  setInterval("playerstatuscheck()",200);
  
  function playerstatuscheck() {
   //console.log(window.page + "-Page");
   //console.log(window.playmark + "-Mark");
   if (window.page == 1) {
    //console.log(niftyplayer('audioword').getState() + "-Gatestate");
    if (niftyplayer('audioword').getState() == "empty") {
      niftyplayer('audioword').play();
    } else if (niftyplayer('audioword').getState() == "playing") {
      if (window.playmark == 0) {
        window.playmark = 1;
      }
    } else if (niftyplayer('audioword').getState() == "finished") {
      if (window.playmark == 1) {
        $('#playbutton').text('Play'); 
        window.playmark = 0; 
      }
    }
    
   } else {
   
    if (niftyplayer('audiotext').getState() == "empty") {
      niftyplayer('audiotext').play();
    } else if (niftyplayer('audiotext').getState() == "playing") {
      if (window.playmark == 0) {
        $('#playbutton').text("Playing");
        window.playmark = 1;
      }
    } else if (niftyplayer('audiotext').getState() == "finished") {
      if (window.playmark == 1) {
        $('#playbutton').text('Play'); 
        window.playmark = 0; 
      }
    }
    
   }
  }
</script>
<style>
a.bgnext
{
position: absolute;
top: 0;
left: 0;
width: 100%;
height:  100%;
}
</style>
</head>
<body>

<h1 id="pageTitle">Vocabulary</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Vocabulary" selected="true">
<div style="text-align:center;padding-top:40px;padding-bottom:40px;"><h2><?php echo $data->word; ?></h2></div>
<div style="text-align:center;padding-top:40px;padding-bottom:40px;"><small>click on screen for next frame</small></div>
<div style="text-align:center;"><a class="bgnext" href="#nextpage" id="definition"> </a></div>

		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audioword" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/vocab/soundword/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/vocab/soundword/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audioword" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
		
</div>

<div id="nextpage" title="Vocabulary" style="min-height: 320px;">

<div style="text-align:center;padding-top:10px;"><?php if(!empty($data->image)) { ?><img src="<?php echo printimage('/datas/vocab/image/'.$data->id); ?>" alt="" /><?php } ?></div>
<div style="text-align:center;padding-top:20px;padding-bottom:20px;"><?php echo $data->translate; ?></div>
<div style="text-align:center;padding-bottom:20px;"><?php if (strstr($data->word, "/")) list($data->word) = explode("/", $data->word); echo preg_replace("/({$data->word})/i","<span style=\"color:red;\">$1</span>",$data->text); ?></div>


<div style="text-align:center;padding-top:40px;padding-bottom:40px;"><small><?php if (!empty($idnext)) echo "click on screen for next item"; else echo "click on screen for returning to course"; ?></small></div>

		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/vocab/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/vocab/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>


</div>
</body>
</html>