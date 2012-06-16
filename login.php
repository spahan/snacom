<?php

require_once('config.php');

// param check
$uid = strtolower(filter_input(INPUT_POST, 'uid', FILTER_VALIDATE_REGEXP, $VALID_USER_FILTER));
if (!$uid) { header('HTTP/1.1 400 Bad Request'); die(); }

$salt = filter_input(INPUT_POST, 'salt', FILTER_VALIDATE_INT);
if (!$salt) { header('HTTP/1.1 400 Bad Request'); die(); }

$hash = filter_input(INPUT_POST, 'hash', FILTER_VALIDATE_REGEXP, $VALID_HASH_FILTER);
if (!$hash) { header('HTTP/1.1 400 Bad Request'); die(); }

session_start();
$_SESSION = array();
session_destroy(); // destroy any old sessions.

// load user data
$fp = @fopen(USER_DATA_DIR . $uid, 'r+');
if (!$fp) {
    // print out a user creation form.
    echo HTML_PAGE_START;
    ?>
        <div style="font-weight:bolder;">Create a new user account</div>
    </div>
    <div class="content">
        <div class="userinfo">
            <form method="POST" action="create.php" onsubmit="this.hash.value = hex_md5(<?php echo APP_SALT;?> + this.hash.value + <?php echo APP_SALT;?>);" class="userinfo" accept-charset=utf-8>
                <input name="uid" type="text" size="15" title="Your name must be 6-20 chars long and can only contain a-z and 0-9" placeholder="username" value="<?php echo $_POST['uid'];?>"/>
                <input name="hash" type="password" size="30" title="Your password must be 8-100 chars long; use special characters at own risk; some chars are forbidden" placeholder="password"/>
                <input type="hidden" name="salt" value=""/>
                <input type="submit" name="send" value="createAccount"/>
            </form>
            <div style="font-size:small;width:auto; float:left;">For details about how user data is handled please refere to the <a href="faq.php">FAQ</a>.</div>
        </div>
    </div>
    <?php
    die(HTML_PAGE_END);
}
$lock = flock($fp, LOCK_EX);
if (!$lock) {
    error_log("ALERT: unable to lock user file for $uid.");
    header('HTTP/1.1 500 Internal Server Error');
    die("can not get lock on user file");
}
function closeAndExit() {global $fp;flock($fp, LOCK_UN); fclose($fp);}
register_shutdown_function('closeAndExit');

$userdata = '';
while (!feof($fp)) { $userdata .= fread($fp, 8192); }
$user = json_decode($userdata, true);
if (!$user) {
    error_log("ALERT: can not decode user data for $uid");
    header('HTTP/1.1 Internal Server Error');
    die("can not decode user data");
} 

// We store the value md5(APP_SALT . password . APP_SALT).
// the provided hash is md5($salt . stored_data . $salt).
// the website needs calculate md5($salt . md5(APP_SALT . password . APP_SALT)) . $salt).
if ($hash === md5( $salt . $user['password'] . $salt)) {
    session_start();
    $user['sid'] = session_id();
    $_SESSION['uid'] = $uid;
    file_put_contents(USER_DATA_DIR . $uid, json_encode($user));
    header('location: /');
} else {
    header('HTTP/1.1 403 Forbidden');
    echo('Wrong Credentials');
}
