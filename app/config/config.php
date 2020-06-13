<?php
define("APPROOT", dirname(__FILE__, 2));
define("ROOT", dirname(__FILE__, 3));
define("URLROOT", 'http://techtalk-ict.epizy.com/');
define("LOCATION", isset($_GET['url']) ? $_GET['url']  :  '');
define("REQUEST", $_SERVER['REQUEST_URI']);
?>