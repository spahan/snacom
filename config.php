<?php
define('FIELD_DATA_DIR', './fields/');
define('USER_DATA_DIR', './users/');
define('FIELD_WIDTH', 58);
define('FIELD_HEIGHT', 10);
define('MINE_DENSITY', 25);
define('GUEST_RETENTION_TIME', 3600); // 1hour
define('APP_SALT', 6247093);

define('USER_GREETING_FORMAT', '<div>Hi <span>%1s</span></div><div>your highest score is <span>%2d</span></div><div>the current score is <span id="score">%3d</span></div><div>you have wrecked <span>%4d</span> ships</div><div><a href="logout.php">logout</a><a href="view.html">your games</a></div>');

define('HTML_PAGE_START','<!DOCTYPE html><html><head>
<meta name="description" content="Snatch Commander is a browser based minesweeper game for booring hours" />
<link rel="stylesheet" type="text/css" href="snacom.css">
<title>Snatch Commander 0.2 - a browser based minesweeper game</title>
<script src="http://api.flattr.com/js/0.6/load.js?mode=auto" async="async" type="text/javascript"></script>
<script src="md5.js" async="async" type="text/javascript"></script>
<script src="jquery.js" type="text/javascript"></script>
<script src="snacom.js" async="async" type="text/javascript"></script>
</head><body>
<div class="wrapper">
    <div id="js-check"><script>$(function() {$(\'#js-check\').remove(); });</script>
	This site requires javascript. Please enable for this site.</div>
    <div class="header"><div class="head"><a href="/"><img src="img/head.png"/></a></div>
<!-- End of header -->'); // html page head for all pages. load should be no trouble as everything should been loaded before from front-page (or will be used later for the frontpage.

define('HTML_PAGE_END', '<div class="footer" id="impressum">miner@spahan.ch (C) by Hanspeter Spalinger 2011; <a href="http://twitter.com/hasp">@hasp</a> jabber:hanfi@jabber.ccc.de <a href="http://blog.spahan.ch">my blog</a>. see <a href="faq.php"> the faq</a> for more infos</div></div></body>');

define('HTML_LOGIN_FORM', '<form method="POST" action="login.php" onSubmit="return this.hash.value = hex_md5(this.salt.value + hex_md5('.APP_SALT.' + this.hash.value + '.APP_SALT.') + this.salt.value);" accept-charset="utf-8";>
    <input name="uid" type="text" size="10" title="Your name must be 6-20 chars long and can only contain a-z and 0-9" placeholder="username"/>
    <input name="hash" type="password" size="17" title="Your password must be 8-100 chars long; use special characters at own risk; some chars are forbidden" placeholder="password"/>
    <input type="hidden" name="salt" value=""/>
    <script>$("input[name=\'salt\']")[0].value = Math.ceil(Math.random()*50000);</script>
    <input type="submit" name="send" value="login/createAccount"/>
</form>
Welcome <span>guest player</span>. To participate in the top10 and review past games, please log in or create a account');

$VALID_USER_FILTER = array('options' => array('regexp' => '/[a-zA-Z]{4}[a-zA-Z0-9]{0,12}/'));
$VALID_HASH_FILTER = array('options' => array('regexp' => '/[a-f0-9]{32}/'));
$VALID_FIELD_FILTER = array('options' => array('regexp' => '/[a-zA-Z]{4}[a-zA-Z0-9]{0,12}#\d*/'));
?>
