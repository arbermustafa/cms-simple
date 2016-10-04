<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */

// APP Environment Global Variables
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));
defined('APP_ENV') || define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));
defined('APP_UPLOAD_PATH') || define('APP_UPLOAD_PATH', realpath(dirname(__FILE__) . '/uploads'));
defined('APP_UPLOAD_URL') || define('APP_UPLOAD_URL', '/uploads');

// App timezone
date_default_timezone_set('Europe/Tirane');

// Error handling
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors' , 1);
}

// Require connector for ckeditor
if (isset($_REQUEST['_rte_file_manager']) && (int) $_REQUEST['_rte_file_manager'] === 1) {
    require_once(APPLICATION_PATH . '/public/assets/vendor/ckeditor/filemanager/connectors/php/connector.php');
    die();
}

// Require bootstrap and routes files
require_once(APPLICATION_PATH . '/src/bootstrap.php');
require_once(APPLICATION_PATH . '/src/routes.php');

// Bootstrap and run the app
$app->run();
