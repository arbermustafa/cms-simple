<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 */

// Autoload
require_once(dirname(__FILE__) . '/../vendor/autoload.php');

// APP Environment
define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));

// Error handling
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors' , 1);
}

// USE STATEMENTS
use \Dotenv\Dotenv;
use \Slim\Slim;
use \Slim\Logger\DateTimeFileWriter;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Illuminate\Database\Capsule\Manager;
use \Illuminate\Events\Dispatcher;
use \Illuminate\Container\Container;
use \Zend\Session\Config\SessionConfig;
use \Zend\Session\SessionManager;
use \Zend\Cache\StorageFactory;
use \Zend\Authentication\AuthenticationService;
use \Zend\Authentication\Storage\Session as SessionStorage;
use \App\Extension\CustomExtension;
use \App\Middleware\Auth\Auth;
use \App\Middleware\Auth\Rules\RequestPathRule;
use \App\Authentication\Adapter;
use \App\Authentication\Acl;

// DOTENV INIT
$env = new Dotenv(dirname(__FILE__) . '/config/', '.env.' . APP_ENV);
$env->load();

// SLIM INIT
Slim::registerAutoloader();
$app = new Slim(array(
    'mode'            => APP_ENV,
    'debug'           => (APP_ENV === 'development'),
    'log.enabled'     => true,
    'log.writer'      => new DateTimeFileWriter(array(
        'path'           => dirname(__FILE__) . '/../data/log',
        'message_format' => PHP_EOL . PHP_EOL . '%label% - %date% - %message%'
    )),
    'cookies.encrypt' => (APP_ENV === 'development'),
    'templates.path'  => dirname(__FILE__) . '/App/View',
    'view'            => new Twig()
));
$app->setName(getenv('APP_NAME'));

// Slim Middleware Init
$app->add(new Auth(array(
    'path'        => '/intranet',
    'passthrough' => array('/intranet/login')
)));

// TWIG INIT
$view = $app->view();
$view->parserOptions = array(
    'debug' => (APP_ENV === 'development'),
    'cache' => dirname(__FILE__) . '/../data/cache'
);
$view->parserExtensions = array(
    new TwigExtension(),
    new Twig_Extension_Debug(),
    new CustomExtension(),
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

$db = new Manager();
$db->addConnection($settings);
$db->setEventDispatcher(new Dispatcher(new Container()));
$db->setAsGlobal();
$db->bootEloquent();
/*$events = new Dispatcher();
$events->listen('illuminate.query', function($query, $bindings, $time, $name)
{
    // Format binding data for sql insertion
    foreach ($bindings as $i => $binding) {
        if ($binding instanceof \DateTime) {
            $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
        } else if (is_string($binding)) {
            $bindings[$i] = "'$binding'";
        }
    }

    // Insert bindings into query
    $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
    $query = vsprintf($query, $bindings);

    // Debug SQL queries
    echo 'SQL: [' . $query . ']';
});

$db->setEventDispatcher($events);*/

// ZEND FRAMEWORK COMPONENTS INIT
// Cache
$app->container->singleton('cache', function()
{
    $cache = StorageFactory::factory(array(
        'adapter' => array(
            'name'    => 'filesystem',
            'options' => array(
                'ttl'             => getenv('CACHE_TTL'),
                'namespace'       => getenv('APP_NAME'),
                'key_pattern'     => '/^[\a-zA-Z0-9_\+\-\[\]]*$/Di',
                'cache_dir'       => dirname(__FILE__) . '/../data/cache',
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

// Session
$app->container->singleton('session', function()
{
    $sessionConfig = new SessionConfig();
    $sessionConfig->setOptions(array(
        'name'                => getenv('APP_NAME'),
        'remember_me_seconds' => getenv('SESSION_TTL'),
        'save_path'           => dirname(__FILE__) . '/../data/session',
        'cookie_httponly'     => true,
        'gc_maxlifetime'      => getenv('SESSION_TTL')
    ));

    $sessionManager = new SessionManager($sessionConfig);

    return $sessionManager;
});

// Auth
$app->container->singleton('auth', function() use ($app)
{
    $authService = new AuthenticationService();
    $storage = new SessionStorage(null, null, $app->session);
    $authService->setStorage($storage);

    return $authService;
});

// Acl
$app->container->singleton('acl', function()
{
    $acl = new Acl();

    return $acl;
});
