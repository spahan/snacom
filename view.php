<?php
require_once('snacom/config.php');
require_once('snacom/functions.php');
require_once('snacom/html.php');
session_start();

html_head(array('jquery.js' => false));
echo '<div id="top10"><?php echo "top10"; ?></div></div>';
$uid = strtolower(filter_input(INPUT_GET, 'u', FILTER_VALIDATE_REGEXP, $VALID_USER_FILTER));
if (!$uid) {
    if (isset($_SESSION['uid'])) {
        $uid = $_SESSION['uid'];
    } else {
		html_title('<div style="font-weight:bolder">You need be logged in to see your games</div></div>');
		html_foot();
        die();
    }
}
// display the users data.
// not thread safe! as we only read, we ignore this here.
$user = json_decode(@file_get_contents(USER_DATA_DIR . $uid), true);
if (!$user) {
	html_title('<div style="font-weight:bolder">No such user</div></div>');
	html_foot();
	die();
}

html_title('Games of <span style="font-weight:bolder">' . $uid . '</span>');
echo '<div class="userdata"><table width="100%"><table width="100%"><tr><th>top10</th><th>recent</th><th>favs</th></th>';
$game_data_format = '<td><a href="view.php?u=%s&g=%s">%s</a>%s</td>';
for ($i = 0; $i < 10; $i++) {
	echo '<tr>';
	if (isset($user['top10'][$i]))
		printf(	$game_data_format, $uid,
					$user['top10'][$i]['id'], 
					$user['top10'][$i]['score'],
					date("j.M Y g:iA",$user['top10'][$i]['date']) );
	else echo '<td/>';
	if (isset($user['last10'][$i]))
		printf(    $game_data_format,$uid,
                    $user['last10'][$i]['id'],
                    $user['last10'][$i]['score'],
                    date("j.M Y g:iA",$user['last10'][$i]['date']) );
    else echo '<td/>';
    if (isset($user['last'][$i])) 
        printf(    $game_data_format, $uid,
                    $user['favorites'][$i]['id'],
                    $user['favorites'][$i]['score'],
                    date("j.M Y g:iA",$user['favorites'][$i]['date']) );
    else echo '<td/>';
	echo "</tr>";
}
echo "</table></div>";

// show game if requestd
$gameID = filter_input(INPUT_GET, 'g', FILTER_VALIDATE_INT, array('options' => array('min_range'=>0)));
if ($gameID) {
	echo '<div class="content"><table id="field">';
	
	if (file_exists(FIELD_DATA_DIR . $uid . '#' . $gameID)) {
    	$game = json_decode(file_get_contents(FIELD_DATA_DIR . $uid . '#' . $gameID));
		for($j=0; $j < count($game); $j++) {
			echo makeViewLine($game[$j]);
		}
	} else {
    	echo '<div style="font-weight:bolder">Did not find game # '.$gameID.'</div></div>';
	}
	echo '</table></div>';
}
html_foot();
?>
