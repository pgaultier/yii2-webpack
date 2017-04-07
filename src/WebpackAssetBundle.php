<?php
/**
 * WebpackAssetBundle.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@ibitux.com>
 * @copyright 2010-2017 Ibitux
 * @license http://www.ibitux.com/license license
 * @version 1.1.0
 * @link http://www.ibitux.com
 * @package sweelix\webpack
 */
namespace sweelix\webpack;

use yii\caching\FileDependency;
use yii\helpers\Json;
use yii\web\AssetBundle;
use Exception;
use Yii;

/**
 * Base webpack assets manager
 *
 * @author Philippe Gaultier <pgaultier@ibitux.com>
 * @copyright 2010-2017 Ibitux
 * @license http://www.ibitux.com/license license
 * @version 1.1.0
 * @link http://www.ibitux.com
 * @package sweelix\webpack
 * @since 1.0.0
 */
class WebpackAssetBundle extends AssetBundle
{
    /**
     * string base cache key
     */
    const CACHE_KEY = 'webpack:bundles:';

    /**
     * @var string name of webpack asset catalog, should be in synch with webpack.config.js
     */
    public $webpackAssetCatalog = 'assets-catalog.json';

    /**
     * @var string default dist directory, should be in synch with webpack.config.js
     */
    public $webpackDistDirectory = 'dist';

    /**
     * @var string base webpack alias
     */
    public $webpackPath;

    /**
     * @var array list of webpack bundles to add
     */
    public $webpackBundles = [];

    /**
     * Merge webpack bundles with classic bundles and cache it if needed
     * @return void
     * @since 1.0.0
     */
    protected function mergeWebpackBundles()
    {
        try {
            if ((isset($this->webpackPath) === true) && (is_array($this->webpackBundles) === true)) {
                $cacheKey = self::CACHE_KEY . get_called_class();
                if ((Yii::$app->cache === null) || (Yii::$app->cache->get($cacheKey) === false)) {
                    $assetsFileAlias = $this->webpackPath . '/' . $this->webpackAssetCatalog;
                    $this->sourcePath = $this->webpackPath . '/' . $this->webpackDistDirectory;
                    $bundles = file_get_contents(Yii::getAlias($assetsFileAlias));
                    $bundles = Json::decode($bundles);
                    if (Yii::$app->cache !== null) {
                        $cacheDependency = new FileDependency([
                            'fileName' => $assetsFileAlias
                        ]);
                        Yii::$app->cache->set($cacheKey, $bundles, 0, $cacheDependency);
                    }
                } else {
                    $bundles = Yii::$app->cache->get($cacheKey);
                }
                foreach($this->webpackBundles as $bundle) {
                    if (isset($bundles[$bundle]['js']) === true) {
                        $this->js[] = $bundles[$bundle]['js'];
                    }
                    if (isset($bundles[$bundle]['css']) === true) {
                        $this->css[] = $bundles[$bundle]['css'];
                    }
                }
            }
        } catch(Exception $e) {
            Yii::error($e->getMessage(), 'sweelix\webpack');
            throw $e;
        }
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->mergeWebpackBundles();
        parent::init();
    }
}
