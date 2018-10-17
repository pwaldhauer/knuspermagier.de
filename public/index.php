<?php

require_once('../vendor/autoload.php');

use Blogchain\Core\Application\Blogchain;
use \Blogchain\Router\Router;

define('APP_URL', (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . $_SERVER['SERVER_NAME']);
define('APP_ROOT', __DIR__ . '/..');

/** @var Blogchain $blogchain */
$blogchain = require_once(__DIR__ . '/../config.php');
$blogchain->initialize();

Blogchain::instance()->get(Router::class)->start();
