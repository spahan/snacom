<?php 
require_once('snacom/config.php');
require_once('snacom/functions.php');
require_once('snacom/html.php');
session_start();

html_head(array(
	'http://api.flattr.com/js/0.6/load.js?mode=auto' => true,
	'md5.js' => true,
	'jquery.js' => false,
	'jquery.socialshareprivacy.min.js' => false,
	'snacom.js' => false));
html_title(<<<EOT
<div style="font-weight:bolder;">Welcome to Snatch Commander.</div>
<div>Some low level criminals have pestered the sea with mines. Your order is to safely guard a UN-medical convoy to innocent war victims.</div>
<div style="font-size:small">Due to hard budget constraints your only equipment is a near field mine detector. It can only detect nearby mines inside a small square.</div>
<div style="font-size:small">Try reach as far down as you can. open fields by click on them, right click marks them; the number shows how many mines are nearby. <a href="faq.php">frequently asked questions</a></div>
EOT
);

if (isset($_SESSION['uid'])) {
	$uid = $_SESSION['uid'];
	$user = getUserData($_SESSION['uid']);
	if (!$user) {
		die('bad session');
	}
	if ($user['sid'] === session_id()) {
		// check if the user has a field. if not create one.
		if (count($user['field']) === 0) {
			makeField($user['field']);
			$user['currentGameID'] = rand();
		}
		html_player($user);
	} else {
		// huh? stall session?
		error_log("ALERT: session for $_SESSION[uid] is looking wrong $_SESSION[sid] != " . session_id());
		echo '<div>BUG! report to <a href="mailto://snacom.spahan.ch">admin</a></div>';
	}
} else {
	// is a guest. setup guest session (or restore old one if still existent)
	if (!isset($_SESSION['gid']) || !file_exists(USER_DATA_DIR . $_SESSION['gid'])) {
		$i = 1;
		while (file_exists(USER_DATA_DIR . $i)) $i++;
		$user = array(  'userID' => $i, 'field' => array());
		$user['sid'] = session_id();
		$_SESSION['gid'] = $i;
		file_put_contents(USER_DATA_DIR . $i, json_encode($user));
	}
	$user = getUserData($_SESSION['gid']);
	if (count($user['field']) === 0) {
		makeField($user['field']);
		$user['currentGameID'] = rand();
	}
	html_login($user);
}
?>
<div class="content scrollbar">
	<table id="field" oncontextmenu="return false;" gid="<?php echo $user['currentGameID']?>"><?php
		for ($j=0; $j < count($user['field']) - 2; $j++) {
			echo makeClientLine($user['field'][$j], $j);
		}
	?></table>
</div>
<?php
file_put_contents(USER_DATA_DIR . $user['userID'], json_encode($user));
html_social();
html_foot();
?>
<script> $(function() {
	$('tr:first-child td:first-child')[0].firstChild.onclick();
	$('tr:first-child td:last-child')[0].firstChild.onclick();
});
</script>
