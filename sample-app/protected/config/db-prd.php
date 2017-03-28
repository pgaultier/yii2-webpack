<?php
/**
 * db-prd.php
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
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=prddb',
    'username' => 'prdname',
    'charset' => 'utf8',
    // 'emulatePrepare' => true,
    'password' => 'prdpass',
    // 'initSQLs' => array('SET time_zone = \'+02:00\''),
    'tablePrefix' => '',
];

