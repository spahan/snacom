<?php
require_once('config.php');
/* log out user */
session_start();
if (@$_SESSION['uid']) {
    $fp = @fopen(USER_DATA_DIR . $_SESSION['uid'], 'r+');
    if (!$fp) {
        error_log("ALERT: user $_SESSION[uid] does not exist while logging out.");
    } else {
        $lock = flock($fp, LOCK_EX);
        if (!$lock) {
            error_log("ALERT: unable to lock user file for $_SESSION[uid].");
        } else {
            function closeAndExit() {global $fp;flock($fp, LOCK_UN); fclose($fp);}
            register_shutdown_function('closeAndExit');
            
            $userdata = '';
            while (!feof($fp)) { $userdata .= fread($fp, 8192); }
            $user = json_decode($userdata, true);
            if ($user['sid'] === session_id()) {
                $user['sid'] = null;
                file_put_contents(USER_DATA_DIR . $_SESSION['uid'], json_encode($user));
            }
        }
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
