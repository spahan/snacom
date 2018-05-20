<?php
define('FIELD_DATA_DIR', './fields/');
define('USER_DATA_DIR', './users/');
define('FIELD_WIDTH', 58);
define('FIELD_HEIGHT', 10);
define('MINE_DENSITY', 25);
define('GUEST_RETENTION_TIME', 3600); // 1hour
define('APP_SALT', 6247093);
define('RC_CLIENT_KEY', '6LfFLNMSAAAAAMrq48_WYRuv-8YWL_PZbZIg3WDM');
define('RC_SERVER_KEY', '6LfFLNMSAAAAABblamLRy1L5U78ZIbneIZt-PylH');

$VALID_USER_FILTER = array('options' => array('regexp' => '/[a-zA-Z]{4}[a-zA-Z0-9]{0,12}/'));
$VALID_HASH_FILTER = array('options' => array('regexp' => '/[a-f0-9]{32}/'));
$VALID_RC_FILTER = array('options' => array('regexp' => '/[a-zA-Z0-9\-_]+/'));
?>
