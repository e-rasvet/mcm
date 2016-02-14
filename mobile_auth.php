<?php

  include_once "config.php";

  $login        = optional_param('username', NULL, PARAM_TEXT);
  $pass         = optional_param('password', NULL, PARAM_TEXT);
 
  if(isset($login) && isset($pass)) {
    if ($USER = get_record("user", array("username" => $login, "password" => $pass))) {

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
    
    
    if ($USER){
      $response = array(
        'logged'  => true,
        'message' => 'Valid login and password'
      );
    } else {
      $response = array(
        'logged'  => false,
        'message' => 'InValid login or password'
      );
    }
  }

  echo json_encode($response);