<?php
$isLocalhost = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false;

define('BASE_URL', $isLocalhost ? '/e-commerce2/' : '/');

define('ASSETS_URL', BASE_URL . 'assets/');
define('CSS_URL', BASE_URL);
define('JS_URL', ASSETS_URL . 'js/');
define('IMG_URL', ASSETS_URL . 'images/');
define('PAGES_URL', BASE_URL . 'pages/');

define('ROOT_PATH', __DIR__ . '/../');