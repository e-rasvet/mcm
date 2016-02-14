<script type="application/x-javascript">
nocheck = 0;
function signuptogroup(){
  $.post("ajax.php", { 'a': 'sign-up'}, function (resp){
    var obj = jQuery.parseJSON( resp );
    if(obj.resp == 'success') {
      window.nocheck = 1;
      clearTimeout(myTimer);
      console.log('ready to start');
      window.location.replace("index.php?id=0");
    } else if(obj.resp == 'noyet') {
      console.log('no yet');
      
      $('#activeusers').html(obj.users);
    }
  });
}

var myTimer = setInterval(function(){signuptogroup()},2000);
signuptogroup();

window.onbeforeunload = function (e) {
  if(nocheck == 0) {
    $.post("ajax.php", { 'a': 'sign-up-close'});
    var message = "Are you sure?";
    if (typeof evt == "undefined") {
        evt = window.event;
    }
    if (evt) {
        evt.returnValue = message;
    }
    return message;
  }
};
</script>
<style>
#list1 { }
#list1 ul { list-style:none; text-align:center; border-top:1px solid #eee; border-bottom:1px solid #eee; padding:10px 0; }
#list1 ul li { margin: 10px 0; padding:0 10px; }
#list1 ul li a { text-decoration:none; color:#eee; }
#list1 ul li a:hover { text-decoration:underline; }
.list-header{text-align: center;font-size: 20px;}
</style>
</head>
<body>

<h1 id="pageTitle">Group Quiz</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<div id="home" title="Group Quiz" selected="true">
<div style="text-align:center;padding-top:10px;"><strong></strong></div>
<div style="padding-top:10px;padding-bottom:10px;">

<div class="list-header">Users in this game:</div>
<div id="list1"><ul id="activeusers">
</ul></div>

<div style="text-align:center;">...waiting for users to join game.</div>

</div>
</div>

</body>
</html>