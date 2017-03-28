<?php
/**
 * SiteController.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\controllers
 */

namespace app\controllers;

use yii\web\Controller;

/**
 * site controller
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\controllers
 * @since 1.3.0
 */
class SiteController extends Controller
{
    /**
     * Show basic application
     *
     * @return \yii\web\Response|string
     * @since 1.3.0
     */
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    /**
     * Show maintenance page
     *
     * @return \yii\web\Response|string
     * @since 1.3.0
     */
    public function actionMaintenance()
    {
        return $this->render('maintenance', []);
    }
}
