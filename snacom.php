<?php
require_once('snacom/config.php');
require_once('snacom/functions.php');
// check user
session_start();
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
} elseif (isset($_SESSION['gid'])) {
    $uid = $_SESSION['gid'];
} else {
    header('HTTP/1.1 403 Forbidden');
    die('no data');
}

$user = getUserData($uid);

// at least x and y must be set >= 0)
$x = filter_input(INPUT_GET, 'x', FILTER_VALIDATE_INT, array('options' => array('min_range'=>0,'max_range'=>FIELD_WIDTH-1)));
$y = filter_input(INPUT_GET, 'y', FILTER_VALIDATE_INT, array('options' => array('min_range'=>0,'max_range'=>count($user['field'])-3)));
$f = filter_input(INPUT_GET, 'f', FILTER_VALIDATE_INT, array('options' => array('min_range'=>0,'max_range'=>1)));

if ((!$x && $x!==0) || (!$y && $y!==0)) {
    header('HTTP/1.1 400 Bad Request');
    die('invalid location data');
}

if ($f === null) {
    // due to async nature we get trouble here some times.
    $result = array();
    if ( $user['field'][$y]{3*$x} == 'M' ) {
        $result['r'] = -1;
        if (isset($_SESSION['gid'])) {
            unlink(USER_DATA_DIR . $_SESSION['gid']);
            header('Content-Type: application/json');
            die(json_encode($result)); // ugly.
        } else {
            $user['field'][$y]{3*$x} = 'B';
            $user['games_played']++;
            $history = array(   'date'=>time(), 
                                'id'=> $user['games_played'], 
                                'data'=> $uid . '#' . $user['games_played'], 
                                'score'=>count($user['field']));
            file_put_contents(FIELD_DATA_DIR . $history['data'], json_encode($user['field']));
            array_push($user['last10'], $history);
            while (count($user['last10']) > 10) array_shift($user['last10']);
            function cmp($a,$b) { return $b['score']-$a['score']; }
            array_push($user['top10'], $history);
            usort($user['top10'], 'cmp');
            while (count($user['top10']) > 10) array_pop($user['top10']);
            $user['field'] = array();
            $result['l'] = $history['data'];
        }
    } else {
        $user['field'][$y]{3*$x} = 'O';
        $result['r'] = $user['field'][$y]{3*$x + 1};
        if ($y >= count($user['field'])-3) {
            // need add another line.
            addLine($user['field']);
            $result['n'] = makeClientLine($user['field'][$y+1],$y+1);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    $user['field'][$y]{3*$x+2} = $f;
}
file_put_contents(USER_DATA_DIR . $uid, json_encode($user));
?>
