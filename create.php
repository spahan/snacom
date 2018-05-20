<?php

require_once('snacom/config.php');
require_once('snacom/recaptchalib.php');

$uid = strtolower(filter_input(INPUT_POST, 'uid', FILTER_VALIDATE_REGEXP, $VALID_USER_FILTER));
if (!$uid) { header('HTTP/1.1 400 Bad Request'); die(); }

$hash = filter_input(INPUT_POST, 'hash', FILTER_VALIDATE_REGEXP, $VALID_HASH_FILTER);
if (!$hash) { header('HTTP/1.1 400 Bad Request'); die(); }

$rcd = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_VALIDATE_REGEXP, $VALID_RC_FILTER);
if (!$rcd) { header('HTTP/1.1 400 Bad Request'); die(); }

if (file_exists(USER_DATA_DIR . $uid)) {
    header('HTTP/1.1 403 Forbidden');
    die('user already exists');
}
$rcc = curl_init('https://www.google.com/recaptcha/api/siteverify');
curl_setopt( $rcc, CURLOPT_RETURNTRANSFER, true);
curl_setopt($rcc, CURLOPT_POST, true);
curl_setopt( $rcc, CURLOPT_POSTFIELDS, array('secret' => RC_SERVER_KEY, 'response' => $rcd));
$rcr = json_decode(curl_exec($rcc));
curl_close($rcc);
if(!$rcr->success)
  die('The reCAPTCHA wasn\'t entered correctly. Go back and try it again.');
// delete previous session data if any.
session_start();
$_SESSION = array();
session_destroy();
session_start();
// generate new user file.
$user = array(  'userID' => $uid, // hte users id, just in case.
                'field' => array(), // the current field
                'top10' => array(), // top 10 of this user
                'last10' => array(), // recent 10 of this user
                'favorites' => array(), // favorites for this user
                'password' => $hash, // md5(APP_SALT . pw . APP_SALT
                'games_played'  => 0, // game count
                'sid' => session_id()); // current session id

file_put_contents(USER_DATA_DIR . $uid, json_encode($user));
$_SESSION['uid'] = $uid;
header('location: /');
?>
