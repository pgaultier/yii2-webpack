<?php
/**
 * main.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\views\layouts
 *
 * @var $this yii\web\View
 * @var $content string
 */

use app\assets\WebpackAsset;

WebpackAsset::register($this);

$this->beginPage(); ?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php echo empty($this->title) ? 'Sweelix Yii2 webpack' : $this->title; ?>
        </title>
        <?php $this->head(); ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
            <?php echo $content; ?>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage();
