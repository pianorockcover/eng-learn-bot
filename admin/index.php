<?php
ini_set('xdebug.var_display_max_depth', 9);
ini_set('xdebug.var_display_max_children', 1256);
ini_set('xdebug.var_display_max_data', 2024);
ini_set('pcre.backtrack_limit', '5000000');
mb_internal_encoding("UTF-8");

use application\Application;

require_once('application/autoload.php');
require_once('../config.php');
require_once('../vendor/autoload.php');


(new Application($config))->run();
