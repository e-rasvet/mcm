<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script type="application/x-javascript">
var playmark = 0;
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

<?php if ($multichoice == false) { ?>
  $('.item-li').click(function() {
    var currentId = $(this).attr('id');
    currentId = currentId.replace("itemid-", "");
    $('#studentansfer').html($(this).text());
    
    if (correctchoice != currentId) $(this).addClass('item-selected'); else $(this).addClass('item-selected-correct');
    
    if (correctchoice == currentId) { $('.correctchoice').html('<span style="color:green">Correct</span>'); $('#nextbtn').toggle(); } else $('.correctchoice').html('<span style="color:red">Incorrect</span>');
    if (correctchoice != currentId) window.setTimeout("history.back();", 2200);
    
    $.post('<?php echo $CFG['wwwroot']."/stat.php"; ?>', { apps: "choice", id: <?php echo $idc; ?>, choice: currentId }, function(data) {
      $('#user-score').html('Score ' + data);
    });
  });
<?php } else { ?>
  $('.item-li').click(function() {
    var currentId = $(this).attr('id');
    currentId = currentId.replace("itemid-", "");
    $('#studentansfer').html($(this).text());
    
    if (correctchoice.indexOf(currentId+",")>-1 || correctchoice.indexOf(","+currentId)>-1) $(this).addClass('item-selected-correct'); else $(this).addClass('item-selected');
    
    if (correctchoice.indexOf(currentId+",")>-1 || correctchoice.indexOf(","+currentId)>-1) {
      $('.correctchoice').html('<span style="color:green">Correct</span><br />Correct choices: <?php echo addslashes(str_replace(",", ", ", $correctchoicesstring)); ?>');
      $('#nextbtn').toggle();
    } else { 
      $('.correctchoice').html('<span style="color:red">Incorrect</span>');
      window.setTimeout("history.back();", 2200);
    }
    
    $.post('<?php echo $CFG['wwwroot']."/stat.php"; ?>', { apps: "choice", id: <?php echo $idc; ?>, choice: currentId }, function(data) {
      $('#user-score').html('Score ' + data);
    });
  });
<?php } ?>
  
});

<?php
echo 'var correctchoice = "'.$correctchoices.'";
';
  ?>
  
function explode( delimiter, string ) {
    var emptyArray = { 0: '' };
    if ( arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined' )
    {
        return null;
    }
    if ( delimiter === ''
        || delimiter === false
        || delimiter === null )
    {
        return false;
    }
    if ( typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object' )
    {
        return emptyArray;
    }
    if ( delimiter === true ) {
        delimiter = '1';
    }
    return string.toString().split ( delimiter.toString() );
}

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

</script>
<style>
.item-li {background-color:#eee; border:1px #666 solid;margin:2px;padding:2px;min-height:36px;}
.item-selected {background-color:#f5a9a9;}
.item-selected-correct {background-color:#bef781;}
#choiceslist {list-style-type:none;padding:0px;margin:0px}
.correctchoice{padding-top:5px;padding-bottom:5px;margin:5px;}
.yourchoice{padding:5px;border:1px #666 solid;margin:5px;}
</style>
</head>
<body>
<h1 id="pageTitle">Question</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Question" selected="true">

<?php if(!empty($data->text)) { ?>
<div style="margin:0 auto;padding:5px;text-align:center;"><?php echo $data->text; ?></div>
<?php } ?>

<?php if(!empty($data->image)) { ?>
<div style="margin:0 auto;padding:5px;text-align:center;"><img src="<?php echo printimage('/datas/choice/image/'.$data->id); ?>" alt="" /></div>
<?php } ?>

<ul id="choiceslist">
<?php
$a = array(1,2,3,4,5,6,7,8,9,10,11,12);
shuffle($a); 
while(list($key,$i)=each($a)) {
  $var = "var".$i;
  if (!empty($data->$var)) {
    echo '<li class="item-li" id="itemid-'.$i.'"><a href="#studentansfers" style="color:#000;text-decoration:none;" ';
    echo ' ><div style="width:100%;height:100%;min-height:36px;">'.$data->$var.'</div></a></li>';
  }
}
?>
</ul>

<?php if(!empty($data->soundtext)) { ?>
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/choice/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/choice/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
<?php } ?>

</div>

<div id="studentansfers" title="Answers">
<?php if(!empty($data->text)) { ?>
<div style="margin:0 auto;padding:5px;text-align:center;"><?php echo $data->text; ?></div>
<?php } ?>

<?php if(!empty($data->image)) { ?>
<div style="margin:0 auto;padding:5px;text-align:center;"><img src="<?php echo $CFG['wwwroot']; ?>/datas/choice/image/<?php echo $data->id; ?>.jpg" alt="" /></div>
<?php } ?>

<?php
echo '<div class="correctchoice">';
echo '</div>';
?>

<div id="studentansfer" class="yourchoice"></div>

<div><div style="width:120px;float:left;font-weight:bold;margin-left:5px;" id="user-score">Score <?php echo $score; ?></div><div style="float:right;display:none;margin-right:10px;" id="nextbtn">
<?php
if (!empty($idnext)) {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/apps/choice/index.php?id=<?php echo $idnext; ?>" target="_webapp">Next -></a>
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