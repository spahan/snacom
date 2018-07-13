<?php

require_once('snacom/config.php');
require_once('snacom/functions.php');
require_once('snacom/html.php');

// param check
$uid = strtolower(filter_input(INPUT_POST, 'uid', FILTER_VALIDATE_REGEXP, $VALID_USER_FILTER));
if (!$uid) { header('HTTP/1.1 400 Bad Request'); die(); }

$pass = filter_input(INPUT_POST, 'pass', FILTER_VALIDATE_REGEXP, $VALID_PASS_FILTER);
if (!$pass) { header('HTTP/1.1 400 Bad Request'); die(); }

session_start();
// save old field data in case we want keep it.
if (isset($_SESSION['gid'])) {
  $guest = json_decode(file_get_contents(USER_DATA_DIR . $_SESSION['gid']), true);
}
$_SESSION = array();
session_destroy(); // destroy any old sessions.

$user = getUserData($uid);
if ($user) {
  if (password_verify($pass, $user['password'])) {
    session_start();
    $user['sid'] = session_id();
    if (isset($guest['field'])) {
      if (count($guest['field']) >= count($user['field'])) {
        $user['field'] = $guest['field'];
        $user['currentGameID'] = $guest['currentGameID'];
      }
    }
    $_SESSION['uid'] = $uid;
    file_put_contents(USER_DATA_DIR . $uid, json_encode($user));
    header('location: /');
  } else {
    header('HTTP/1.1 403 Forbidden');
    echo('Wrong Credentials');
  }
} else { // no such user
  // print out a user creation form.
  html_head(array(
    'js/md5.js' => true,
    'js/jquery.js' => false,
    'js/snacom.js' => true,
    'https://www.google.com/recaptcha/api.js' => true));
  html_title("No user <span style=\"font-weight:bold\">$uid</span> found. You may create it");
  html_create($uid);
  html_foot();
}
