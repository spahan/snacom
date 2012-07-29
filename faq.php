<?php 
require_once('snacom/config.php'); 
require_once('snacom/html.php');
session_start();

html_head(array('jquery.js' => false));
html_title('<div style="font-weight:bolder;">frequently answered questions</div>');
?>
    <div class="content">
        <ul id="faq">
            <li>
                <div>General</div>
                <dl>
                    <dt>WTF is Snatch Commander ?</dt>
                    <dd>Snatch Commander is a <a href="http://en.wikipedia.org/wiki/Minesweeper_%28video_game%29">Minesweeper</a> like game. Instead of clearing a field, the player is asked to continue move down as far as possible.</dd>
                    <dt>Who are you</dt>
                    <dd>I am a (assumed) self-aware beeing from planet Earth. I have a <a href="https://spahan.ch">Intertube site</a> too.
                    </dd>
                    <dt>Why does it fail?</dt>
                    <dd>Snatch Commander is in development. It is absolutely posible, that you hit bugs or something does not work. Please feel free to <a href="mailto:miner@spahan.ch">drop me a mail</a></dd>
                </dl>
            </li>
            <li>
                <div>Account</div>
                <dl>
                    <dt>Why do i need a account?</dt>
                    <dd>accounts are not needed to play the game. However, if you have a account, you participate in the top10, can review past games, and (soon available) mark games as "favorites" (for example to show funny games to friends or to show bugs to me)</dd>
                    <dt>How do I create a account?</dt>
                    <dd>Just fill in a username and password at the login prompt. If the user does not exist, you are asked if you want to create the account.</dd>
                    <dt>What Data is collected about me?</dt>
                    <dd>The game itself stores your username, password, last 10 played fields, your best 10 fields, and any fields you have marked as "favorite". During the beta phase the apache access logging is turned on to make debugging easier.<br/> The website includes a social-bar at the bottom including various social network links. We use the <a href="http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html">"2 clicks for privacy"-plugin developed by heise.de</a> for improved privacy.<br/>Finally, this site includes a <a href="https://flattr.com/">flattr button</a>.</dd>
                    <dt>What Data is collected about me if I don't use a account?</dt>
                    <dd> The game safes your current play field, and a temporary session. This session is deleted after 1 hour of inactivity. See above question for details about the social bar and apache setup.</dd>
                    <dt>where is my data stored?</dt>
                    <dd>User data is stored on this host. Located in "Rechenzentrum 13" of <a href="http://www.hetzner.de/">Hetzner online</a> (Germany). The data transfered by social-media plugins are stored somewhere unkown to me (most likely US)</dd>
                </dl>
            </li>
            <li>
                <div>Playing</div>
                <dl>
                    <dt>What is my target?</dt>
                    <dd>Every time you open a field on the bottom, a new line will apear. Try open as many lines as you can</dd>
                    <dt>How do i open a field</dt>
                    <dd>click it. if the field contains a mine, the game is lost.</dd>
                    <dt>What are those numbers?</dt>
                    <dd>the numbers tell how many fields with mines are around this field.</dd>
                    <dt>How can i mark a field with a mine?</dt>
                    <dd>Right-click it (ctrl-click if you have a single-button mouse). The client will prevent you from open such fields.</dd>
                    <dt>How do i know which fields contains mines?</dt>
                    <dd>Use your brain! The human brain is capable to do high level logic deduction. If used correctly, it is able to tell where the mines are.</dd>
                </dl>
            </li>
			<li>
				<div>Technical</div>
				<dl>
					<dt>I use NoScript. What domains do i need whitelist to play?</dt>
					<dd>Only snacom.ch is required to play. At registration recaptcha needs google.com</dd>
					<dt>How can I hide my ass from you?</dt>
					<dd>But I am sooo nice!!!... Well, just use <a href="https://www.torproject.org/">Tor</a></dd>
					<dt>I cracked the highscore, why am i not listed?</dt>
					<dd>The high score is updated every 5 minutes. Just wait.</dd>
					<dt>IT does not work nice on smartphones!</dt>
					<dd>Yes, i am working on that. I am still not sure how to interact nicely on touch-based devices.. Any ideas? Drop me a mail</dd>
					<dt>My question is not answered here. Where do i ask?</dt>
					<dd>Drop me a mail at miner@spahan.ch</dd>
				</dl>
			</li>
        </ul>
    </div>
	<script type="text/javascript">
        function hideShow(e) {
            $(this).next('dl').toggleClass('hidden');
        }
		$('#faq > li > div').click(hideShow);
		$('#faq > li > dl').toggleClass('hidden');
    </script>
<?php
html_foot();
?>
