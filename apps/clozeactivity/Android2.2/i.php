<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script type="application/x-javascript">
var playmark = 0;

var totalcount = <?php echo count($correctchoices); ?>;
var currentcount = 0;

$(document).ready(function(){
  var score = <?php echo $score; ?>;
  
  <?php if(!empty($data->soundtext)) { ?>
  $('#playbutton').text("Playing");
  
  $('#playbutton').click(function() {
    if (window.playmark == 0) {
      niftyplayer('audiotext').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
    }
    return false;
  });
  <?php } ?>

  
});


<?php if(!empty($data->soundtext)) { ?>

setInterval("playerstatuscheck()",200);
  
  function playerstatuscheck() {
    if (niftyplayer('audiotext').getState() == "empty") {
      niftyplayer('audiotext').play();
    } else if (niftyplayer('audiotext').getState() == "playing") {
      if (window.playmark == 0) {
        window.playmark = 1;
      }
    } else if (niftyplayer('audiotext').getState() == "finished") {
      if (window.playmark == 1) {
        $('#playbutton').text('Play'); 
        window.playmark = 0; 
      }
    }
  }
<?php } ?>


function checkword(e, mask, id){
  if(mask.substr(0, e.value.length).toLowerCase() != e.value.toLowerCase()) {
    $("#var_id_"+id).addClass("disable");
  } else {
    $("#var_id_"+id).removeClass("disable");
    if(e.value.toLowerCase() == mask.toLowerCase()) {
      $("#var_id_"+id).attr("disabled","disabled");
      currentcount++;
      
      if(currentcount == totalcount) {
        $('#nextbtn').show();
      }
    }
  }
}

$(document).ready(function() {
  setTimeout("$('.inputfield').attr(\"autocomplete\", \"off\")",1000);
});
</script>

<style>
.disable{background-color: #FF9999;}
</style>

</head>
<body>
<h1 id="pageTitle">Cloze activity</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Question" selected="true">

<?php if(!empty($data->text)) { 

foreach($correctchoices as $k => $v){
    $html = '<span data-url="'.$v.'"><input type="text" value="" name="var['.$k.']" id="var_id_'.$k.'" style="
        border: none;
        margin: 0;
        padding: 0;
        width: 100px;
        border-bottom: 1px dashed #333;
    " onkeyup="checkword(this, \''.$v.'\', \''.$k.'\')" autocomplete="off" class="inputfield"></span>';
    $data->text = str_replace('['.$k.']', $html, $data->text);
}
?>
<div style="margin:0 auto;padding:5px;text-align:center;"><?php echo $data->text; ?></div>
<?php } ?>

<?php if(!empty($data->image)) { ?>
<div style="margin:0 auto;padding:5px;text-align:center;"><img src="<?php echo printimage('/datas/clozeactivity/image/'.$data->id); ?>" alt="" /></div>
<?php } ?>

<?php if(!empty($data->soundtext)) { ?>
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/clozeactivity/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/clozeactivity/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
<?php } ?>

<div><div style="width:120px;font-weight:bold;margin-left:5px;" id="user-score">Score <?php echo $score; ?></div><div style="display:none;margin-right:10px;" id="nextbtn">
<?php
if (!empty($idnext)) {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/apps/clozeactivity/index.php?id=<?php echo $idnext; ?>" target="_webapp">Next -></a>
<?php
} else {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" target="_webapp">Return to course</a>
<?php
}
?>
</div></div>

</div>


</body>
</html>