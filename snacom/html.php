<?php
require_once('config.php');

function html_head($scripts) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="description" content="Snatch Commander is a browser based minesweeper game for booring hours" />
		<link rel="stylesheet" type="text/css" href="snacom.css">
		<title>Snatch Commander 0.2 - a browser based minesweeper game</title>
<?php
	$script_format = '<script src="%1s" %2s type="text/javascript"></script>';
	foreach($scripts as $path => $async) {
		printf( $script_format, $path, ($async)?'async="async"':'');
	}
	echo '</head>';
}

function html_title($text) {
?>
<body>
	<div class="wrapper">
		<div id="js-check">
			<script>$(function() {$('#js-check').remove(); });</script>
			This site requires javascript. Please enable for this site.
		</div>
		<div class="header">
			<div class="head">
				<a href="/"><img src="img/head.png"/></a>
			</div>
			<div class="welcome"><?php echo $text;?></div>
			<hr/>
			<div class="top10">
<?php
	$top = json_decode(file_get_contents('top10.json'), true);
	echo '<div><span style="font-weight:bold">' . $top[0]['ships_wrecked'] . '</span> ships wrecked so far. Top Players:</div>';
	$top_format = '<div>%1$s. <a href="view.php?u=%2$s">%2$s</a> (%3$s)</div>';
	for ($i=1; $i < count($top); $i++) {
		printf($top_format, $i, $top[$i]['uid'], $top[$i]['score']);
	}
	echo '</div></div>';
}

function html_foot() {
?>
		<div class="footer">
			miner@spahan.ch (C) by Hanspeter Spalinger 2012;
			<a href="http://twitter.com/hasp">@hasp</a>
			jabber:hanfi@jabber.ccc.de
			<a href="http://blog.spahan.ch">my blog</a>
			. see <a href="faq.php"> the faq</a> for more infos
		</div>
	</div>
</body>
</html>
<?php
}

function html_player($player) {
?>
<div class="userinfo">
<div>Hi <span><?php echo $player['userID']?></span></div>
<div>your highest score is <span><?php echo (count($player['top10']) > 0)? $player['top10'][0]['score']:0?></span></div>
<div>the current score is <span id="score"><?php echo count($player['field'])?></span></div>
<div>you have wrecked <span><?php echo $player['games_played']?></span> ships</div>
<div style="float:right; width:auto;">
	<a style="padding-right:5px;" href="logout.php">logout</a> <a href="view.php">your games</a>
</div>
</div>
<?php
}

function html_login($data) {
?>
<div class="userinfo">
	<form method="POST" action="login.php" onSubmit="return this.hash.value = hex_md5(this.salt.value + hex_md5(<?php echo APP_SALT?> + this.hash.value + <?php echo APP_SALT?>) + this.salt.value);" accept-charset="utf-8";>
    	<input name="uid" type="text" size="10" title="Your name must be 6-20 chars long and can only contain a-z and 0-9" placeholder="username"/>
    	<input name="hash" type="password" size="10" title="Your password must be 8-100 chars long; use special characters at own risk; some chars are forbidden" placeholder="password"/>
    	<input type="hidden" name="salt" value=""/>
    	<script>$("input[name=\'salt\']")[0].value = Math.ceil(Math.random()*50000);</script>
    	<input type="submit" name="send" value="login/createAccount"/>
	</form>
Welcome <span>guest player</span>. score <span id="score"><?php echo count($data['field']); ?></span>. To review games and participate in Top10 create a account:
</div>
<?php
}

function html_create($uid) {
?>
<div class="usercreate">
	<form method="POST" action="create.php" onsubmit="return this.hash.value = hex_md5(<?php echo APP_SALT;?> + this.hash.value + <?php echo APP_SALT;?>);" accept-charset=utf-8>
		<div style="float:right">
        <?php
            require_once('snacom/recaptchalib.php');
            echo recaptcha_get_html(RC_CLIENT_KEY);
        ?>
        </div>
		<div title="Your name must be 6-20 chars long and can only contain a-z and 0-9">
			<input name="uid" type="text" size="15" placeholder="username" value="<?php echo $uid;?>"/>
			please choose a creative <span style="font-weight:bold">username</span>
		</div>
		<div title="Your password must be 8-100 chars long; use special characters at own risk; some chars are forbidden">
			<input name="hash" type="password" size="30" placeholder="password"/>
			do not reuse important<span style="font-weight:bold">passwords</span>
		</div>
		<input type="hidden" name="salt" value=""/>
		<input type="submit" name="send" value="createAccount"/>
	</form>
	<div style="clear:both">For details about how user data is handled please refere to the <a href="faq.php">FAQ</a>.</div>
</div>
<?php
}

function html_social() {
?>
<div class="footer" id="social">
	<div class="twitter">
<!-- begin twitter tweet -->
<a href="http://twitter.com/share" class="twitter-share-button" data-text="Good luck sailor!" data-count="none">Tweet</a><script async="async" type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<!-- end twitter tweet -->
	</div>
	<div class="flattr">
<!-- flattr -->
<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://snacom.ch"></a>
<noscript><a href="http://flattr.com/thing/401325/Snatch-Commander" target="_blank">
<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
<!-- end of flattr -->
	</div>
</div>
<?php
}
?>
