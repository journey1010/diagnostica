<?php
define('_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'], false);
define('_RCONTROLLER', _ROOT_PATH .'/controller/', false);
define('_RMODEL', _ROOT_PATH. '/model/', false);
define('_RVIEWS', _ROOT_PATH. '/views/', false);
define('PROTOCOL', (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === 'on' ? 'https' : 'http'), false);
define('_BASE_URL', PROTOCOL . '://' . $_SERVER['HTTP_HOST'], false);
define('_RASSETS', _BASE_URL.'/assets/', false);