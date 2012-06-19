<?php 
require_once('snacom/config.php');
require_once('snacom/functions.php');
session_start();
echo HTML_PAGE_START;
?>
        <div style="font-weight:bolder;">Welcome to Snatch Commander.</div>
        <div> 
            Some low level criminals have pestered the sea with mines. Your order is to safely guard a UN-medical convoy to innocent war victims.
        </div>
        <div style="font-size:small">
           Due to hard budget constraints your only equipment is a near field mine detector. It can only detect nearby mines inside a small square.
        </div>
        <div style="font-size:small">Try reach as far down as you can. open fields by click on them, right click marks them; the number shows how many mines are nearby. <a href="faq.php">frequently asked questions</a></div>
        <div id="top10"><?php echo "top10"; ?></div>
    </div>
	<div class="userinfo">
        <?php // check user session.
            if (isset($_SESSION['uid'])) {
                $user = getUserData($_SESSION['uid']); // open and lock user data.
                if ($user['sid'] === session_id()) {                    
                    // check if the user has a field. if not create one.
                    if (count($user['field']) === 0) {
                        makeField($user['field']);
                    }
                    // print user data
                    printf( USER_GREETING_FORMAT, $user['userID'], (count($user['top10']) > 0)? $user['top10'][0]['score']:0, count($user['field']), $user['games_played']);
                    
                } else {
                    // huh? stall session?
                    error_log("ALERT: session for $_SESSION[uid] is looking wrong $_SESSION[sid] != " . session_id());
                    echo '<div>BUG! report to <a href="mailto://snacom.spahan.ch">admin</a></div>';
                }
            } else { 
                echo HTML_LOGIN_FORM;
                // is a guest. setup guest session.
                if (!isset($_SESSION['gid']) || !file_exists(USER_DATA_DIR . $_SESSION['gid'])) {
                    $i = 1;
                    while (file_exists(USER_DATA_DIR . $i)) $i++;
                    $user = array(  "userID" => "${i}", "field" => array());
                    $user['sid'] = session_id();
                    $_SESSION['gid'] = $i;
                    file_put_contents(USER_DATA_DIR . $i, json_encode($user));
                }
                $user = getUserData($_SESSION['gid']);

                if (count($user['field']) === 0) {
                    makeField($user['field']);
                }
            }
        ?>
    </div>
    <div class="content scrollbar">
        <table id="field"><?php
            for ($j=0; $j < count($user['field']) - 2; $j++) {
                echo makeClientLine($user['field'][$j], $j);
            }
        ?></table>
    </div>
    <div class="footer" id="social">
        <div class="twitter">
<!-- begin twitter tweet --
<a href="http://twitter.com/share" class="twitter-share-button" data-text="Good luck sailor!" data-count="none">Tweet</a><script async="async" type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
!-- end twitter tweet -->
        </div>
        <div class="flattr">
<!-- flattr -->
<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://snacom.ch"></a>
<noscript><a href="http://flattr.com/thing/401325/Snatch-Commander" target="_blank">
<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
<!-- end of flattr -->
        </div>
    </div>
    <script> $(function() {
        $('tr:first-child td:first-child')[0].onclick();
        $('tr:first-child td:last-child')[0].onclick();
    });
    </script>
<?php echo HTML_PAGE_END; 
file_put_contents(USER_DATA_DIR . $user['userID'], json_encode($user));
?>
