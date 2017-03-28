<?php
/**
 * WebpackAsset.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\assets
 */

namespace app\assets;

use sweelix\webpack\WebpackAssetBundle;
use yii\web\View;

/**
 * Base webpack assets
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version 1.3.1
 * @link http://www.sweelix.net
 * @package application\assets
 * @since 1.3.0
 */
class WebpackAsset extends WebpackAssetBundle
{
    /**
     * @var string base webpack alias
     */
    public $webpackPath = '@app/assets/webpack';

    /**
     * @var array list of webpack bundles to add
     */
    public $webpackBundles = [
        'manifest',
        'app'
    ];

    /**
     * @inheritdoc
     */
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

}
