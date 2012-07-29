#!/usr/bin/php
<?php
/* creates top10 and cleans out the field data */

require_once('config.php');
chdir(__DIR__ . '/..');

// clean out non-referenced data files
$d = dir(FIELD_DATA_DIR);
$fu = null;
$u = null;
while ($entry = $d->read()) {
	$f = split('#', $entry);
	if (count($f) == 2 ) {
		if (file_exists(USER_DATA_DIR . $f[0])) {
			$u = file_get_contents(USER_DATA_DIR . $f[0]);
			if ( !preg_match("/\"$entry\"/", $u) ) {
				unlink(FIELD_DATA_DIR . $entry);
			}
		} else {
			unlink(FIELD_DATA_DIR . $entry);
			continue;
		}
	}
}

// create top 10 and clean out old guest files.
$d = dir(USER_DATA_DIR);
$t = array();
$total = 0;
while ($entry = $d->read()) {
	if (strpos($entry, '.') === false) {
		if (preg_match('/\d+/',$entry) === 1) {
			// clean up old guest files.
			$s = stat( USER_DATA_DIR . $entry);
			if ((time() - $s['mtime']) > GUEST_RETENTION_TIME) {
				unlink(USER_DATA_DIR . $entry);
			}
		} else  {
			$u = json_decode(file_get_contents(USER_DATA_DIR . $entry), true);
			$total += $u['games_played'];
			$s = 0;
			foreach ($u['top10'] as $i=>$v) {
				if ($v['score'] > $s) $s = $v['score'];
			}
			$t[] = array('uid'=> $u['userID'], 'score'=> $s);
		}
	}
}
function cmp($a,$b) {
	return $b['score'] - $a['score'];
}
usort($t, "cmp");
$t = array_slice($t, 0, 10);
array_unshift($t, array('ships_wrecked' => $total));
file_put_contents('top10.json',json_encode($t));
?>
