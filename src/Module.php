<?php
/**
 * Module.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.0.1
 * @link http://www.sweelix.net
 * @package sweelix\webpack
 */

namespace sweelix\webpack;

use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\console\Application as ConsoleApplication;


/**
 * Webpack Module definition
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.0.1
 * @link http://www.sweelix.net
 * @package sweelix\webpack
 * @since XXX
 */
class Module extends BaseModule implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof ConsoleApplication) {
            $this->mapConsoleControllers($app);
        }
    }

    /**
     * Update controllers map to add console commands
     * @param ConsoleApplication $app
     * @since 1.0.0
     */
    protected function mapConsoleControllers(ConsoleApplication $app)
    {
        $app->controllerMap['webpack'] = [
            'class' => 'sweelix\webpack\commands\WebpackController',
        ];
    }
}
