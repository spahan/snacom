<?php
require_once('config.php');
// Helpers to handle mine fields.

/* Minefield data structure description and usage.
   A mine field is a array of strings. each string describes a single line of the minefield.
   The first line never has any mines (to prevent negative array indexes...i am lazy.)
   each field uses 3 chars:
     1. 'field state': C is closed, O is open, M is mine. B is Boomed and only exists in finished games.
     2. how many mines are near this field. is set even if field is mine. max is 9 or we did something wrong.
     3. if the user has flagged the field. (maybe we want more user states....)

   The last line of the field is always a hidden blank line with no mines. 
   Adding lines is done by append a new empty lines (FIELD_WIDTH times 'C00'),
   and then randomly place mines in the second last line (which didnt had any mines so far).
 
*/

// adds a new line to a field.
function addLine(&$field) {
    $cl = count($field);
    $field[] = str_repeat("C00", FIELD_WIDTH);
    for ($i=0; $i < FIELD_WIDTH; $i++) {
        if (mt_rand(0,100) <= MINE_DENSITY) {
            $field[$cl-1]{3*$i} = 'M';
            if ($i > 0) {
                $field[$cl-2]{3*($i-1)+1} = $field[$cl-2]{3*($i-1)+1} +1;
                $field[$cl-1]{3*($i-1)+1} = $field[$cl-1]{3*($i-1)+1} +1;
                $field[$cl  ]{3*($i-1)+1} = $field[$cl  ]{3*($i-1)+1} +1;
            }
                $field[$cl-2]{3*($i  )+1} = $field[$cl-2]{3*($i  )+1} +1;
                $field[$cl-1]{3*($i  )+1} = $field[$cl-1]{3*($i  )+1} +1;
                $field[$cl  ]{3*($i  )+1} = $field[$cl  ]{3*($i  )+1} +1;
            if ($i < FIELD_WIDTH-1) {
                $field[$cl-2]{3*($i+1)+1} = $field[$cl-2]{3*($i+1)+1} +1;
                $field[$cl-1]{3*($i+1)+1} = $field[$cl-1]{3*($i+1)+1} +1;
                $field[$cl  ]{3*($i+1)+1} = $field[$cl  ]{3*($i+1)+1} +1;
            }
        }
    }
}

// create a new field
function makeField(&$field) {
    $field[] = str_repeat("C00", FIELD_WIDTH);
    $field[] = str_repeat("C00", FIELD_WIDTH);
    $field[] = str_repeat("C00", FIELD_WIDTH);
    while (count($field) < FIELD_HEIGHT) {
        addLine($field);
    }
}

// transfroms a given line to the client side representation.
// j is the line number.
function makeClientLine($line, $j) {
    $f = '<td x="%d" y="%d"><img src="img/%s" onclick="checkField(this.parentNode)" oncontextmenu="return toggleFlag(this.parentNode)"/></td>';
    $result = '<tr>';
    for ($i=0;$i< FIELD_WIDTH; $i++) {
        if ($line{$i*3} == 'O') {
             $result .= sprintf($f, $i, $j, 'open' . $line{$i*3+1} . '.png');
        } elseif ($line{$i*3+2} == '1') {
            $result .= sprintf($f, $i, $j, 'flagged.png');
        } else {
            $result .= sprintf($f, $i, $j, 'closed.png');
        }
    }
    $result .= '</tr>';
    return $result;
}

// transform field data to html to show to user.
function makeViewLine($line) {
    $f = '<td><img src="img/%s"/></td>';
    $result = "\n\n<tr>";
    for ($i=0;$i< FIELD_WIDTH; $i++) {
		switch ($line{$i*3}) {
			case 'O':
            	$result .= sprintf($f, 'open' . $line{$i*3+1} . '.png'); break;
			case 'C':
            	$result .= sprintf($f, ( $line{$i*3+2} == '1' ) ? 'badflag.png' : 'closed.png'); break;
			case 'M':
            	$result .= sprintf($f, ( $line{$i*3+2} == '1' ) ? 'goodflag.png' : 'mine.png'); break;
			case 'B':
				$result .= sprintf($f, 'boom.png'); break;
			default:
				error_log("WARNING: illegal field state in game!");
        }
    }
    $result .= '</tr>';
    return $result;
}

// open and lock user data.
function getUserData($uid) {
    global $fp;
    $fp = @fopen(USER_DATA_DIR . $uid, 'r+');
	if (!$fp) return false;
    if (!flock($fp, LOCK_EX)) return false;
    // as we use a lock now, we want close it cleanly on shutdown.
    function closeAndExit() {global $fp;flock($fp, LOCK_UN); fclose($fp);}
    register_shutdown_function('closeAndExit');

    // as we already opened the file, we just read it in. usualy only requires 1 read.
    // other way would be to use file_get_contents... this is more consistent.
    $userdata = '';
    while (!feof($fp)) { $userdata .= fread($fp, 8192); }
    return json_decode($userdata, true);

}

