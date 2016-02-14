<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/ios-drag-drop.js"></script>


<style type="text/css">
li {
  list-style: none;
  font-size:13px;
}

li.answ a {
  text-decoration: none;
  color: #000;
  margin: 10px;
  border: 3px dashed #999;
  background: #eee;
  padding: 12px 4px 13px 2px;
  display: block;
}

li.quest {
  text-decoration: none;
  color: #000;
  margin: 10px;
  border: 3px solid #999;
  background: #eee;
  padding: 10px 4px 10px 2px;
  display: block;
}

li.answblue a {
  text-decoration: none;
  color: #000;
  margin: 10px;
  border: 3px dashed #999;
  background: #99cccc;
  padding: 12px 4px 13px 2px;
  display: block;
}

li.questblue {
  text-decoration: none;
  color: #000;
  margin: 10px;
  border: 3px solid #999;
  background: #99cccc;
  padding: 10px 4px 10px 2px;
  display: block;
}

*[draggable=true] {
  -moz-user-select:none;
  -khtml-user-drag: element;
  cursor: move;
}

*:-khtml-drag {
  background-color: rgba(238,238,238, 0.5);
}

li.answ a:hover:after {
  content: ' (drag me)';
}

ul {
  margin: 0;
  padding: 0;
}

li.questover {
  text-decoration: none;
  color: #000;
  margin: 10px;
  border: 3px solid #6666FF;
  background: #CCCCFF;
  padding: 10px 4px 10px 2px;
  display: block;
}

#questionslist {
  width: 40%;
  float: left;
  position: relative;
  margin-top: 0;
}

#answerslist {
  width: 40%;
  float: left;
  position: relative;
  margin-top: 0;
}

</style>
<script>

var addEvent = (function () {
  if (document.addEventListener) {
    return function (el, type, fn) {
      if (el && el.nodeName || el === window) {
        el.addEventListener(type, fn, false);
      } else if (el && el.length) {
        for (var i = 0; i < el.length; i++) {
          addEvent(el[i], type, fn);
        }
      }
    };
  } else {
    return function (el, type, fn) {
      if (el && el.nodeName || el === window) {
        el.attachEvent('on' + type, function () { return fn.call(el, window.event); });
      } else if (el && el.length) {
        for (var i = 0; i < el.length; i++) {
          addEvent(el[i], type, fn);
        }
      }
    };
  }
})();

</script>

<body>
<h1 id="pageTitle">DragDrope activity</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>
<a href="#" class="button" id="playbutton" target="_webapp" style="display:none;"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>

<div id="home" title="Question" selected="true">

<div>
  <div id="questionslist">
  <ul>
<?php
foreach ($questions as $k => $v) {
    echo '<li class="quest" id="question_'.$k.'">';
    $var = "soundtexta".$k;
    if ($data->{$var} == 1)
      echo '<img src="'.$CFG['wwwroot'].'/images/small_play.png" width="18" height="18" style="margin-right: 6px;cursor: pointer;" id="playq'.$k.'" />';
    echo $v.'</li>';
}
?>
  </ul>
  </div>
  
  <div id="answerslist">
  <ul>
<?php
foreach ($answers as $k => $v) {
    echo '<li class="answ">';
    $var = "soundtextvar".$k;
    if ($data->{$var} == 1)
      echo '<img src="'.$CFG['wwwroot'].'/images/small_play.png" width="18" height="18" style="margin: 14px 8px 14px 16px;float:left;cursor: pointer;" id="playa'.$k.'" />';
    echo '<a id="answers_'.$k.'" href="#">'.$v.'</a></li>';
}
?>
  </ul>
  </div>
</div>
  <script>
  
  var activelink = $( "#answers_1" );
  var correct = 0;
  var maxcorrect = <?php echo count($answers); ?>;
  window.playmark = 0;
  
  var links = document.querySelectorAll('li.answ > a'), el = null;
  for (var i = 0; i < links.length; i++) {
    el = links[i];
  
    el.setAttribute('draggable', 'true');
  }
  
<?php
foreach($answers as $k => $v){
?>
  var q<?php echo $k; ?> = $('#question_<?php echo $k; ?>');
  
  addEvent(q<?php echo $k; ?>, 'dragover', function (e) {
    if (e.preventDefault) e.preventDefault(); // allows us to drop
    e.dataTransfer.dropEffect = 'copy';
    return false;
  });
  
  addEvent(q<?php echo $k; ?>, 'dragenter', function (e) {
    this.className = 'questover';
    return false;
  });
  
  addEvent(q<?php echo $k; ?>, 'dragleave', function () {
    this.className = 'quest';
  });

  addEvent(q<?php echo $k; ?>, 'drop', function (e) {
    if (e.stopPropagation) e.stopPropagation(); // stops the browser from redirecting...why???

    if(activelink.attr("id") == "answers_<?php echo $k; ?>") {
      activelink.parent().remove();
      correct++;
      
      if(correct >= maxcorrect) {
        $('#nextbtn').toggle();
      }
    } else 
      this.className = 'quest';
  });
  
<?php
}
?>
  
<?php
foreach($answers as $k => $v){
?>
  $( "#answers_<?php echo $k; ?>" ).mousedown(function() {
    activelink = $( "#answers_<?php echo $k; ?>" );
  });
<?php
}
?>
  
  </script>
  
  <div style="clear:both;"></div>

<div><div style="width:120px;font-weight:bold;margin-left:5px;" id="user-score">Score <?php echo $score; ?></div>

<div style="display:none;margin-right:10px;" id="nextbtn">
<?php
if (!empty($idnext)) {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/apps/dragdrope/index.php?id=<?php echo $idnext; ?>" target="_webapp">Next -></a>
<?php
} else {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" target="_webapp">Return to course</a>
<?php
}
?>
</div></div>
  
  <div style="clear:both;"></div>
  
<div>

<?php 
foreach($questions as $k => $v){
  $sname = 'soundtextvar'.$k;
  if(!empty($data->{$sname})) { ?>
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="<?php echo $sname; ?>" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/dragdrope/soundtextvar/".$idc."_".$k.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/dragdrope/soundtextvar/".$idc."_".$k.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="<?php echo $sname; ?>" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
<?php 
  }
} ?>

<?php 
foreach($answers as $k => $v){
  $sname = 'soundtexta'.$k;
  if(!empty($data->{$sname})) { ?>
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="<?php echo $sname; ?>" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/dragdrope/soundtexta/".$idc."_".$k.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/dragdrope/soundtexta/".$idc."_".$k.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="<?php echo $sname; ?>" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
<?php 
  }
} ?>
</div>
  
</div>


<script>
<?php
foreach ($questions as $k => $v) {
?>
$("#<?php echo 'question_'.$k; ?>").click(function() {
  if (window.playmark == 0) {
    window.playmark = 1;
    $('#<?php echo 'question_'.$k; ?>').attr('class', 'questblue');
    niftyplayer('<?php echo 'soundtextvar'.$k; ?>').play();
    $("#<?php echo 'playq'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play_gr.png'; ?>");
    $('#playbutton').show();
    setTimeout('$("#<?php echo 'playq'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play.png'; ?>");', 1500);
  }
});
$("#<?php echo 'playq'.$k; ?>").click(function() {
  if (window.playmark == 0) {
    window.playmark = 1;
    $('#<?php echo 'question_'.$k; ?>').attr('class', 'questblue');
    niftyplayer('<?php echo 'soundtextvar'.$k; ?>').play();
    $("#<?php echo 'playq'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play_gr.png'; ?>");
    $('#playbutton').show();
    setTimeout('$("#<?php echo 'playq'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play.png'; ?>");', 1500);
  }
});
$(document).ready(function() {
setTimeout("niftyplayer('<?php echo 'soundtextvar'.$k; ?>').registerEvent('onSongOver', '$(\"#playbutton\").hide();window.playmark=0;$(\"#<?php echo 'question_'.$k; ?>\").attr(\"class\", \"quest\");');", 1000);
});
<?php
}


foreach ($answers as $k => $v) {
?>
$("#<?php echo 'answers_'.$k; ?>").click(function() {
  if (window.playmark == 0) {
    window.playmark = 1;
    $('#<?php echo 'answers_'.$k; ?>').parent().attr('class', 'answblue');
    niftyplayer('<?php echo 'soundtexta'.$k; ?>').play();
    $("#<?php echo 'playa'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play_gr.png'; ?>");
    $('#playbutton').show();
    setTimeout('$("#<?php echo 'playa'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play.png'; ?>");', 1500);
  }
});
$("#<?php echo 'playa'.$k; ?>").click(function() {
  if (window.playmark == 0) {
    window.playmark = 1;
    $('#<?php echo 'answers_'.$k; ?>').parent().attr('class', 'answblue');
    niftyplayer('<?php echo 'soundtexta'.$k; ?>').play();
    $("#<?php echo 'playa'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play_gr.png'; ?>");
    $('#playbutton').show();
    setTimeout('$("#<?php echo 'playa'.$k; ?>").attr("src", "<?php echo $CFG['wwwroot'].'/images/small_play.png'; ?>");', 1500);
  }
});
$(document).ready(function() {
setTimeout("niftyplayer('<?php echo 'soundtexta'.$k; ?>').registerEvent('onSongOver', '$(\"#playbutton\").hide();window.playmark=0;$(\"#<?php echo 'answers_'.$k; ?>\").parent().attr(\"class\", \"answ\");');", 1000);
});
<?php
}
?>
</script>


</body>
</html>