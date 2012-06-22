<?php
require_once('snacom/config.php');
require_once('snacom/functions.php');
/* log out user */
session_start();
if (@$_SESSION['uid']) {
	$user = getUserData($_SESSION['uid']);
	if (@$user['sid' === session_id()) {
		$user['sid'] = null;
		file_put_contents(USER_DATA_DIR . $_SESSION['uid'], json_encode($user));
	}
}
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header('Location: /');
