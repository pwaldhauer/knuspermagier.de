<?php

require_once 'vendor/autoload.php';

define('APP_URL', (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . $_SERVER['SERVER_NAME']);
define('APP_ROOT', __DIR__ . '/..');

$blogchain = require_once(__DIR__ . '/config.php');
$blogchain->initialize();

/** @var \Blogchain\Core\Search\SearchProvider $searchProvider */
$searchProvider = $blogchain->get(\Blogchain\Core\Search\SearchProvider::class);

/** @var \Blogchain\Core\Database\PostDatabase $database */
$database = $blogchain->get(\Blogchain\Core\Database\PostDatabase::class);

/** @var \Blogchain\Core\PostProcessor\PostProcessorChain $chain */
$chain = $blogchain->get(\Blogchain\Core\PostProcessor\PostProcessorChain::class);

foreach ($database->listPosts(['published' => true], 0, 300) as $post) {

    $chain->processPost($post);

    /** @var \pwaio\Blogchain\Model\Post $post */
    $searchProvider->addToIndex($post);

    echo "Added post: " . $post->id . PHP_EOL;

}

