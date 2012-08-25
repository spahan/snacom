<?php

require_once('snacom/config.php');
require_once('snacom/functions.php');
require_once('snacom/html.php');

// param check
$uid = strtolower(filter_input(INPUT_POST, 'uid', FILTER_VALIDATE_REGEXP, $VALID_USER_FILTER));
if (!$uid) { header('HTTP/1.1 400 Bad Request'); die(); }

$salt = filter_input(INPUT_POST, 'salt', FILTER_VALIDATE_INT);
if (!$salt) { header('HTTP/1.1 400 Bad Request'); die(); }

$hash = filter_input(INPUT_POST, 'hash', FILTER_VALIDATE_REGEXP, $VALID_HASH_FILTER);
if (!$hash) { header('HTTP/1.1 400 Bad Request'); die(); }

session_start();
// save old field data in case we want keep it.
if (isset($_SESSION['gid'])) {
	$guest = json_decode(file_get_contents(USER_DATA_DIR . $_SESSION['gid']), true);
}
$_SESSION = array();
session_destroy(); // destroy any old sessions.

$user = getUserData($uid);
if ($user) {
	// We store the value md5(APP_SALT . password . APP_SALT).
	// the provided hash is md5($salt . stored_data . $salt).
	// the website needs calculate md5($salt . md5(APP_SALT . password . APP_SALT)) . $salt).
	if ($hash === md5( $salt . $user['password'] . $salt)) {
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
		'http://api.flattr.com/js/0.6/load.js?mode=auto' => true,
		'md5.js' => true,
		'jquery.js' => false,
		'snacom.js' => true));
	html_title("No user <span style=\"font-weight:bold\">$uid</span> found. You may create it");
	html_create($uid);
	html_foot();
}
