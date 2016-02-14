<?php
  
  include_once "config.php";

  $login        = optional_param('login', NULL, PARAM_TEXT);
  $pass         = optional_param('pass', NULL, PARAM_TEXT);
  $act          = optional_param('act', NULL, PARAM_TEXT);

  $urlinfo = parse_url($CFG['wwwroot']);

  if ($act == "course") {
    $id          = optional_param('id', NULL, PARAM_INT);
    $_SESSION['currentcourse'] = $id;
    setcookie("mcm_course", $id, time() + 1200, "/", $urlinfo['host']);
    die();
  }

  if(isset($login) && isset($pass))
    if ($USER = get_record("user", array("username" => $login, "password" => md5($pass)))) {
      $_SESSION['userid'] = $USER->id;
    } else if(is_array($LDAPCFG)) {
      if (ldap_login($login, $pass)) {
        if(!$USER = get_record("user", array("username" => $login, "password" => md5($pass)))) {
          $add = array();
          $add['username'] = $login;
          $add['password'] = md5($pass);
          $add['firstname'] = 'ldapuser';
          $add['lastname'] = 'ldapuser';
          $add['email'] = 'ldapuser';
          
          if(!empty($add['username']))
            $id = insert_record("user", $add);
          
          $USER = get_record("user", array("username" => $login, "password" => md5($pass)));
        }
      }
    }
   
  if (empty($USER->id)) {
    echo "error";
  } else {
    if ($login)
      setcookie("mcm_username", $login, time() + 1200, "/", $urlinfo['host']);
      
    if ($pass) 
      setcookie("mcm_password", md5($pass), time() + 1200, "/", $urlinfo['host']);
  }
