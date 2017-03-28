<?php
/**
 * console.php
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
    'name' => 'Sweelix Yii2 webpack console application',
    'id' => 'sweelix/yii2-webpack',
    'sourceLanguage' => 'fr',
    'language' => 'fr',
    'timezone' => 'Europe/Paris',
    'extensions' => require(dirname(__DIR__) . '/vendor/yiisoft/extensions.php'),
    'controllerNamespace' => 'app\commands',
    'bootstrap' => ['log', 'webpack'],
    'controllerMap' => [
    ],
    'modules' => [
        'webpack' => [
            'class' => 'sweelix\webpack\Module',
        ]
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
];

