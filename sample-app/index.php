<?php
/**
 * index.php
 *
 * PHP version 5.6+
 *
 * Initial application script
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\base
 */

$configFile = __DIR__.'/protected/config/web.php';
$modePath = __DIR__.'/protected/config';

$debug = isset($_SERVER['YII_DEBUG']) ? true : false;

if (isset($_SERVER['YII_ENV']) && (in_array($_SERVER['YII_ENV'], array('prd', 'dev')) === true)) {
    if (file_exists($modePath.'/web-'.$_SERVER['YII_ENV'].'.php') === true) {
        $configFile = $modePath.'/web-'.$_SERVER['YII_ENV'].'.php';
    }
    defined('YII_ENV') or define('YII_ENV', $_SERVER['YII_ENV']);
}

if (isset($_SERVER['YII_MAINTENANCE']) === true) {
    defined('YII_MAINTENANCE') or define('YII_MAINTENANCE', true);
} else {
    defined('YII_MAINTENANCE') or define('YII_MAINTENANCE', false);
}

if ($debug === true) {
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_ENV') or define('YII_ENV', 'dev');
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    defined('YII_ENV') or define('YII_ENV', 'prd');
}

require(__DIR__ . '/protected/vendor/autoload.php');
require(__DIR__ . '/protected/vendor/yiisoft/yii2/Yii.php');

$config = require($configFile);

(new yii\web\Application($config))->run();
