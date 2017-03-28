<?php
/**
 * console-prd.php
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

$config = require(__DIR__ . '/console.php');

// $config['components']['db'] = require(__DIR__ . '/db-prd.php');
$config['params'] = require( __DIR__ . '/params-prd.php');

return $config;
