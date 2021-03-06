<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 */

// Autoload
require_once(APPLICATION_PATH . '/vendor/autoload.php');

// DOTENV INIT
$env = new \Dotenv\Dotenv(APPLICATION_PATH . '/src/config/', '.env.' . APP_ENV);
$env->load();

// SLIM INIT
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array(
    'mode'            => APP_ENV,
    'debug'           => (APP_ENV === 'development'),
    'log.enabled'     => true,
    'log.writer'      => new \Slim\Logger\DateTimeFileWriter(array(
        'path'           => APPLICATION_PATH . '/data/log',
        'message_format' => PHP_EOL . PHP_EOL . '%label% - %date% - %message%'
    )),
    'cookies.encrypt' => (APP_ENV === 'development'),
    'templates.path'  => APPLICATION_PATH . '/src/App/View',
    'view'            => new \Slim\Views\Twig()
));
$app->setName(getenv('APP_NAME'));

// Slim Middleware Init
$app->add(new \App\Middleware\Auth\Auth(array(
    'path'        => '/intranet',
    'passthrough' => array('/intranet/login')
)));

// TWIG INIT
$view = $app->view();
$view->parserOptions = array(
    'debug' => (APP_ENV === 'development'),
    'cache' => APPLICATION_PATH . '/data/cache'
);
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new Twig_Extension_Debug(),
    new \App\Extension\CustomExtension(),
);

// Twig Global Constant
$view->getInstance()->addGlobal('nearbyPagesLimit', (int) getenv('DB_PAGERANGE_PAGINATION'));

// DB ELOQUENT INIT
$settings = array(
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'options'   => array(
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION
    ),
    'prefix'    => ''
);

$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($settings);
$db->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container()));
$db->setAsGlobal();
$db->bootEloquent();

// ZEND FRAMEWORK COMPONENTS INIT
// Cache
$app->container->singleton('cache', function()
{
    $cache = \Zend\Cache\StorageFactory::factory(array(
        'adapter' => array(
            'name'    => 'filesystem',
            'options' => array(
                'ttl'             => getenv('CACHE_TTL'),
                'namespace'       => getenv('APP_NAME'),
                'key_pattern'     => '/^[\a-zA-Z0-9_\+\-\[\]]*$/Di',
                'cache_dir'       => APPLICATION_PATH . '/data/cache',
                'dir_permission'  => getenv('CACHE_DIRPERMISSION'),
                'file_permission' => getenv('CACHE_FILEPERMISSION')
            )
        ),
        'plugins' => array(
            'serializer',
            'exception_handler'    => array(
                'throw_exceptions' => (APP_ENV === 'development')
            )
        )
    ));

    return $cache;
});

// Session Manager
$app->container->singleton('sessionManager', function()
{
    $sessionConfig = new \Zend\Session\Config\SessionConfig();
    $sessionConfig->setOptions(array(
        'name'                => getenv('APP_NAME'),
        'remember_me_seconds' => getenv('SESSION_TTL'),
        'save_path'           => APPLICATION_PATH . '/data/session',
        'cookie_httponly'     => true,
        'gc_maxlifetime'      => getenv('SESSION_TTL')
    ));

    $sessionManager = new \Zend\Session\SessionManager($sessionConfig);
    \Zend\Session\Container::setDefaultManager($sessionManager);

    return $sessionManager;
});

// Session Container
$app->container->singleton('session', function()
{
    $container = new \Zend\Session\Container(getenv('APP_NAME'));

    return $container;
});

// Auth
$app->container->singleton('auth', function()
{
    $app = \Slim\Slim::getInstance(getenv('APP_NAME'));
    $authService = new \Zend\Authentication\AuthenticationService();
    $storage = new \Zend\Authentication\Storage\Session(null, null, $app->sessionManager);
    $authService->setStorage($storage);

    return $authService;
});

// Acl
$app->container->singleton('acl', function()
{
    $acl = new \App\Authentication\Acl();

    return $acl;
});

// Shortcodes Handler
$app->container->singleton('shortcode', function()
{
    return new \App\Utility\Shortcodes();
});

// Facebook
$app->container->singleton('facebook', function()
{
    $app = \Slim\Slim::getInstance(getenv('APP_NAME'));
    $session = $app->session;
    $app_id = (\App\Service\Setting::getByKey('fb_app_id')) ? \App\Service\Setting::getByKey('fb_app_id') : 'default';
    $app_secret = (\App\Service\Setting::getByKey('fb_app_secret')) ? \App\Service\Setting::getByKey('fb_app_secret') : 'default';

    $facebook = new \Facebook\Facebook(array(
        'app_id'                  => $app_id,
        'app_secret'              => $app_secret,
        'default_graph_version'   => 'v2.2',
        'persistent_data_handler' => new \App\Social\Facebook\CustomPersistentDataHandler($app)
    ));

    return $facebook;
});

// LinkedIn
$app->container->singleton('linkedin', function()
{
    $app = \Slim\Slim::getInstance(getenv('APP_NAME'));
    $request = $app->request;
    $api_key = (\App\Service\Setting::getByKey('in_client_id')) ? \App\Service\Setting::getByKey('in_client_id') : 'default';
    $api_secret = (\App\Service\Setting::getByKey('in_client_secret')) ? \App\Service\Setting::getByKey('in_client_secret') : 'default';
    $callback_url = $request->getUrl() . $app->urlFor('social.in');

    $linkedin = new \App\Social\LinkedIn\LinkedIn(array(
        'api_key'      => $api_key,
        'api_secret'   => $api_secret,
        'callback_url' => $callback_url
    ));

    return $linkedin;
});
