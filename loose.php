<?php 
require_once('snacom/config.php'); 
require_once('snacom/html.php');
session_start();

html_head(array('jquery.js' => false));
html_title('<div style="font-weight:bolder;">BLAST! You shiped into a mine!</div>');
?>
    <div class="header">
		<a href="/">
            <img style="margin:auto;display:block;" src="img/loose.jpg"/></a>
            This is the end! At least for that poor old convoy ship. Good one she was.</br>
            You and your crew silently watch her go down from the safe-boats.</br>
            At arrival, your head welcomes you, gratulates you for the safe comeback and gets you to your next mission. Good luck Captain!
    </div>
    <div class="footer" id="copyright">
        image is <a href="//en.wikipedia.org/wiki/en:Creative_Commons" title="w:en:Creative Commons">CC
        <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.en">Attribution-Share Alike 3.0 Unported</a> license.
        taken from <a href="http://en.wikipedia.org/wiki/File:New_flame_sinking.JPG">redcoat10@wikimedia</a>
    </div>
<?php
html_foot();
?>
