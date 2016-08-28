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

    // Users
    $app->get('/users/list/:page', 'App\Controller\Admin\Users:index')->name('intranet.users.list');
    $app->map('/users', 'App\Controller\Admin\Users:add')->via('GET', 'POST')->name('intranet.users.add');
    $app->map('/users/edit/:id', 'App\Controller\Admin\Users:edit')->via('GET', 'POST')->name('intranet.users.edit');
    $app->get('/users/:id', 'App\Controller\Admin\Users:delete')->name('intranet.users.delete');

    // Posts categories
    $app->get('/categories/list', 'App\Controller\Admin\Categories:index')->name('intranet.categories.list');
    $app->map('/categories', 'App\Controller\Admin\Categories:add')->via('GET', 'POST')->name('intranet.categories.add');
    $app->map('/categories/edit/:id', 'App\Controller\Admin\Categories:edit')->via('GET', 'POST')->name('intranet.categories.edit');
    $app->get('/categories/:id', 'App\Controller\Admin\Categories:delete')->name('intranet.categories.delete');

    // Slider
    $app->get('/slides/list/:page', 'App\Controller\Admin\Slides:index')->name('intranet.slides.list');
    $app->map('/slides', 'App\Controller\Admin\Slides:add')->via('GET', 'POST')->name('intranet.slides.add');
    $app->map('/slides/edit/:id', 'App\Controller\Admin\Slides:edit')->via('GET', 'POST')->name('intranet.slides.edit');
    $app->get('/slides/:id', 'App\Controller\Admin\Slides:delete')->name('intranet.slides.delete');

    // Error
    $app->get('/unauthorized', 'App\Controller\Admin\Error:unauthorized')->name('intranet.unauthorized');
});
