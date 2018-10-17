<?php

error_reporting(E_ALL);
date_default_timezone_set('Europe/Berlin');
setlocale(LC_TIME, 'de_DE.utf8');

define('IS_LIVE', isset($_SERVER) && $_SERVER['HTTP_HOST'] === 'knuspermagier.de');

use Blogchain\Core\Application\Blogchain;
use Blogchain\Core\Application\BlogchainConfiguration;
use Blogchain\Core\Authentication\User;
use Blogchain\Core\Authentication\ArrayUserProvider;

$container = Blogchain::instance();
$container->share(BlogchainConfiguration::class, function () {
    $config = new BlogchainConfiguration();

    $config->publicPath = __DIR__ . '/public';
    $config->assetPath = __DIR__ . '/public/assets';
    $config->contentPath = __DIR__ . '/content';
    $config->templatesPath = __DIR__ . '/templates';
    $config->assetUrl = APP_URL . '/assets';

    $config->perPage = 5;
    $config->debug = !IS_LIVE;

    $config->randomFooterAssetsPath = __DIR__ .'/theme/randomfooter';


    $linkNames = [
        'Martin' => 'https://martinwolf.org',
        'Nina' => 'http://nina-jaeger.de',
        'Daniel' => 'http://losstopscha.de',
        'Marcel' => 'http://uarrr.org',
        'Hannah' => 'https://noe.io',
        'Markus' => 'https://markuscisler.com'
    ];

    $config->plugins = [
        new \Blogchain\Router\BlogchainRouter(),
        new \Blogchain\Api\BlogchainApi(),
        new \Blogchain\Web\BlogchainWebAccess(),

        new \Knuspermagier\BlogchainRandomFooter\RandomFooter()
    ];

    $config->chain = [
        new \Blogchain\Core\PostProcessor\DateSlugPostProcessor(),
        new \Blogchain\Core\PostProcessor\TitlePostProcessor(),
        new \Blogchain\Core\PostProcessor\DataPostProcessor(),

        // Eigentlich nur bei api
        new \Blogchain\Core\PostProcessor\MediaPostProcessor(),

        new \Blogchain\Core\PostProcessor\ShortCodeProcessor(),
        new Knuspermagier\BlogchainNameAutoLinker\NameAutoLinker($linkNames),

        new \Blogchain\Core\PostProcessor\MarkdownPostProcessor(),
        new \Blogchain\Core\PostProcessor\CodeHighlightPostProcessor(),


    ];

    return $config;
});

/*
if (IS_LIVE) {
    $container->add(\pwaio\Blogchain\Cache\CacheInterface::class, \pwaio\Blogchain\Cache\RedisCache::class);
}

*/

$container->share(\Blogchain\Core\Search\SearchProvider::class, \Blogchain\Core\Search\RedisSearchProvider::class);

$container->share(\Blogchain\Core\Resolver\PostResolver::class, function () use ($container) {
    return new \Blogchain\Core\Resolver\FilesystemDateSlugPostResolver($container);
});

$container->share(\Blogchain\Core\Authentication\Session::class, \Blogchain\Core\Authentication\RedisSession::class);

$container->share(\Blogchain\Core\Authentication\UserProvider::class, function() {
    return ArrayUserProvider::withUsers(
        array_map(function($item) {
            return new User(
                $item['username'], $item['email'], $item['password']
            );
        }, json_decode(file_get_contents('./.cred.json'), true))
    );
});

return $container;