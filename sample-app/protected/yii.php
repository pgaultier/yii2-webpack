<?php
/**
 * yiic.php
 *
 * PHP version 5.6+
 *
 * Initial console script
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\base
 */

$configFile = __DIR__.'/config/console.php';
$modePath = __DIR__.'/config';

// fcgi doesn't have STDIN and STDOUT defined by default
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$debug = isset($_SERVER['YII_DEBUG']) ? true : false;

//Find mode and corresponding config file
if (isset($_SERVER['YII_ENV']) && (in_array($_SERVER['YII_ENV'], array('prd', 'dev')) === true)) {
    if (file_exists($modePath.'/console-'.$_SERVER['YII_ENV'].'.php') === true) {
        $configFile = $modePath.'/console-'.$_SERVER['YII_ENV'].'.php';
    }
    defined('YII_ENV') or define('YII_ENV', $_SERVER['YII_ENV']);
} else {
    //Find mode and corresponding config file
    $debug = false;
    $currentMode = 'prd';
    foreach (['prd', 'dev'] as $mode) {
        if (file_exists($modePath.'/'.$mode)) {
            $currentMode = $mode;
            if (file_exists($modePath.'/console-'.$mode.'.php')) {
                $configFile = $modePath.'/console-'.$mode.'.php';
            }
            break;
        }
    }

    //Set debug regarding mode
    if (in_array($currentMode, ['dev'])) {
        $debug = true;
    }
}

if ($debug === true) {
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_ENV') or define('YII_ENV', 'prd');
}

$config = require($configFile);
$exitCode = (new yii\console\Application($config))->run();
exit($exitCode);
