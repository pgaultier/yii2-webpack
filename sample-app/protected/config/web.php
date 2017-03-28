<?php
/**
 * web.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\config
 */

return [
    'basePath' => dirname(__DIR__),
    'name' => 'Sweelix Yii2 sample web application',
    'id' => 'sweelix/yii2-webpack',
    'sourceLanguage' => 'fr',
    'language' => 'fr',
    'timeZone' => 'Europe/Paris',
    'extensions' => require(dirname(__DIR__) . '/vendor/yiisoft/extensions.php'),
    'controllerNamespace' => 'app\controllers',
    'catchAll' => require( __DIR__ . '/maintenance.php'),
    'bootstrap' => ['log'],
    'modules' => [
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        /*
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /**/
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '',
                    'route' => 'site/index',
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@app/locales',
                    'forceTranslation' => true,
                    'useMoFile' => false,
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
        ],
    ],
];
