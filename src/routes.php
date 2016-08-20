<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */

// Default homepage
$app->get('/', 'App\Controller\Index:index')->name('index');

// Static routes
$app->get('/sitemap', 'App\Controller\Index:sitemap')->name('sitemap');
$app->get('/sitemap.xml', 'App\Controller\Index:sitemapGoogle')->name('sitemap.xml');
$app->get('/imprint', 'App\Controller\Index:imprint')->name('imprint');
$app->get('/rss', 'App\Controller\Index:rss')->name('rss');
$app->get('/search/:page', 'App\Controller\Search:index')->name('search');

// For test purposes
$app->get('/test', 'App\Controller\Test:index')->name('test');

// 404 Response
$app->notFound(function() use ($app)
{
    $app->render('Error/404.html', array(), 404);
});

// 500 Response
$app->error(function(\Exception $e) use ($app)
{
    $app->log->error($e);
    $app->render('Error/500.html', array(), 500);
});

// Frontend routes
// Single page/post
$app->get('/:slug', 'App\Controller\Content:post')->name('post');
// Archive
$app->get('/archive/:slug/:page', 'App\Controller\Content:archive')->name('archive');

// Backend routes
$app->group('/intranet', function() use ($app)
{
    // User
    $app->map('/login', 'App\Controller\Admin\Auth:login')->via('GET', 'POST')->name('intranet.login');
    $app->map('/profile', 'App\Controller\Admin\Auth:profile')->via('GET', 'POST')->name('intranet.profile');
    $app->get('/logout', 'App\Controller\Admin\Auth:logout')->name('intranet.logout');

    // Dashboard
    $app->get('/dashboard', 'App\Controller\Admin\Index:dashboard')->name('intranet.dashboard');

    // Website settings
    $app->map('/settings', 'App\Controller\Admin\Settings:index')->via('GET', 'POST')->name('intranet.settings');

    // Error
    $app->get('/unauthorized', 'App\Controller\Admin\Error:unauthorized')->name('intranet.unauthorized');
});
