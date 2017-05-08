Yii2 Webpack integration
========================

This extension allow the developper to use [Webpack 2](https://webpack.js.org/) as the asset manager.

Webpack2 comes preconfigured with the following loaders 
 * javascript
 * typescript
 * sass
 * less
 * css


[![Latest Stable Version](https://poser.pugx.org/sweelix/yii2-webpack/v/stable)](https://packagist.org/packages/sweelix/yii2-webpack)
[![License](https://poser.pugx.org/sweelix/yii2-webpack/license)](https://packagist.org/packages/sweelix/yii2-webpack)
<!--
[![Build Status](https://api.travis-ci.org/pgaultier/yii2-webpack.svg?branch=master)](https://travis-ci.org/pgaultier/yii2-webpack)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/?branch=master)
-->


[![Latest Development Version](https://img.shields.io/badge/unstable-devel-yellowgreen.svg)](https://packagist.org/packages/sweelix/yii2-webpack)
<!--
[![Build Status](https://travis-ci.org/pgaultier/yii2-webpack.svg?branch=devel)](https://travis-ci.org/pgaultier/yii2-webpack)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/badges/quality-score.png?b=devel)](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/?branch=devel)
[![Code Coverage](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/badges/coverage.png?b=devel)](https://scrutinizer-ci.com/g/pgaultier/yii2-webpack/?branch=devel)
-->

Installation
------------

If you use Packagist for installing packages, then you can update your `composer.json like this :

``` json
{
    "require": {
        "sweelix/yii2-webpack": "*"
    }
}
```

Warning
-------

When `vendor` modules use typescript, typescript code can collide. In order to avoid this, you should update your `tsconfig.json` to exclude `vendor` modules

### Generic `tsconfig.json`

```json
{
    "compilerOptions": {
        "sourceMap": true,
        "noImplicitAny": true,
        "module": "commonjs",
        "target": "es5",
        "jsx": "react",
        "allowJs": true
    }
}
```

### Amended `tsconfig.json`

If `vendor` modules are in folder **vendor** you should update your `tsconfig.json` like this:
 
```json
{
    "compilerOptions": {
        "sourceMap": true,
        "noImplicitAny": true,
        "module": "commonjs",
        "target": "es5",
        "jsx": "react",
        "allowJs": true
    },
    "exclude": [
        "node_modules",
        "vendor"
    ]
}
```

Howto use it
------------

Add extension to your console configuration to enable CLI commands

``` php
return [
    // add webpack to console bootstrap to attach console controllers 
    'bootstrap' => ['log', 'webpack'],   
    //....
    'modules' => [
        'webpack' => [
            'class' => 'sweelix\webpack\Module',
        ],
        // ...
    ],
];
```

### Generate everything from scratch (init webpack stuff)

```
php protected/yii webpack
```

 * **Generating package.json**
   * **Relative path to composer.json ?** If you are in main directory, this is probably `composer.json` 
   * **Application name ?** Application name which will be used in package.json
   * **Author ?** Your name 
   * **Description ?** Description of package.json 
   * **License ?** License of the application

 * **Generating webpack-yii2.json**
   * **Webpack assets path relative to package.json** This is where you will write your front app (`protected/assets/webpack` for example)  
   * **Webpack assets source directory ?** Name of the directory (inside webpack assets relative path) where you will create sources `src`
   * **Webpack assets distribution directory ?** Name of the directory (inside webpack assets relative path) where bundles will be generated `dist`
   * **Webpack assets distribution scripts directory ?** Name of the directory (inside `dist`) where scripts will be stored (`js`)
   * **Webpack assets distribution styles directory ?** Name of the directory (inside `dist`) where styles will be stored (`css`)
   * **Webpack catalog filename ?** Name of catalog file which will allow the asset manager to find the bundles

 * **Generating webpack.config.js** 
 
if you need to regenerate one of the files, you can use the following CLI commands :
 
  * `php protected/yii webpack/generate-config` : regenerate `webpack-yii2.json`
  * `php protected/yii webpack/generate-package` : regenerate `package.json`
  * `php protected/yii webpack/generate-webpack` : regenerate `webpack.config.js`
  * `php protected/yii webpack/generate-generate-typescript-config` : regenerate `tsconfig.json`
  
### Sample application structure
  
If your application has the following directory structure : 
  
 * *index.php*
 * *composer.json*
 * **protected**
   * *yii* (console script)
   * **assets**
     * *WebpackAsset.php*
     * **webpack**
       * **src**
         * *app.ts*
         * **css**
           * *app.css*
   * *...*      
  
#### Run webpack init to prepare application 
  
The typical answer when running `php protected/yii webpack` would be :
   
 * **Generating package.json**
   * **Relative path to composer.json ?** Leave default value
   * **Application name ?** Leave default value
   * **Author ?** Leave default value 
   * **Description ?** Leave default value 
   * **License ?** Leave default value

 * **Generating webpack-yii2.json**
   * **Webpack assets path relative to package.json** `protected/assets/webpack`  
   * **Webpack assets source directory ?** Leave default value
   * **Webpack assets distribution directory ?** Leave default value
   * **Webpack assets distribution scripts directory ?** Leave default value
   * **Webpack assets distribution styles directory ?** Leave default value
   * **Webpack catalog filename ?** Leave default value

 * **Generating webpack.config.js** 

 * **Generating tsconfig.json** 

Application structure with generated files will be

 * *index.php*
 * *composer.json*
 * *package.json*
 * *webpack-yii2.json*
 * *webpack.config.js*
 * *tsconfig.json*
 * **protected**
   * *yii* (console script)
   * **assets**
     * *WebpackAsset.php*
     * **webpack**
       * *assets-catalog.json* -> generated by webpack
       * **dist** -> generated by webpack
         * **js**
           * *js bundles*
         * **css**
           * *css bundles*
       * **src**
         * *app.ts*
         * **css**
           * *app.css*
   * *...*      

#### Create Webpack aware asset

```php
namespace app\assets;

use sweelix\webpack\WebpackAssetBundle;

class WebpackAsset extends WebpackAssetBundle
{

    /**
     * @var bool enable caching system (default to false)
     */
    public $cacheEnabled = false;

    /**
     * @var \yii\caching\Cache cache name of cache to use, default to `cache`
     */
    public $cache = 'cache';

    /**
     * @var string base webpack alias (do not add /src nor /dist, they are automagically handled)
     */
    public $webpackPath = '@app/assets/webpack';

    /**
     * @var array list of webpack bundles to publish (these are the entries from webpack)
     * the bundles (except for the manifest one which should be in first position) must be defined
     * in the webpack-yii2.json configuration file
     */
    public $webpackBundles = [
        'manifest',
        'app'
    ];

}
```  

#### Generate the assets

For development run

```
webpack
```
or to enable automatic build on file change
```
webpack --watch
```


For production run

```
webpack -p
```

#### Add files to your repository

When your assets are ready, you have to make sure following files are added to your repository :

 * `package.json` node package management
 * `webpack.config.js` needed to run webpack
 * `webpack-yii2.json` needed by webpack.config.js to define you app entry points and the target directories
 * `tsconfig.json` needed by webpack.config.js to handle Typescript files

 * `<appdir>/assets/webpack/assets-catalog.json` to let the webpack aware asset find the dist files
 * `<appdir>/assets/webpack/dist` to keep the assets (they are not dynamically generated when asset is registered)
 * `<appdir>/assets/webpack/src` because you want to keep your sources :-)
 

### File `webpack-yii2.json` explained
 
```json
{
    "sourceDir": "protected\/assets\/webpack",
    "entry": {
        "app": "./app.ts"
    },
    "commonBundles": [
        "manifest"
    ],
    "externals": {

    },
    "subDirectories": {
        "sources": "src",
        "dist": "dist"
    },
    "assets": {
        "styles": "css",
        "scripts": "js"
    },
    "catalog": "assets-catalog.json"
}
```

#### Specific to yii2-webpack module

 * **sourceDir** relative path to the directory where assets will be managed
 * **subDirectories** subpath (in *< sourceDir >*) of sources and distribution files
 * **assets** subpath (in *< sourceDir >/< subDirectories.dist >*) of styles and scripts assets
 * **catalog** name of assets catalog, must be in synch with `WebpackAssetBundle::$webpackAssetCatalog`
  
#### Mapped to Webpack vars
  
 * **entry** object syntax entry points [Webpack entry documentation](https://webpack.js.org/concepts/entry-points/#object-syntax)
 * **commonBundles** explicit vendor chunk [Webpack CommonChunkPlugin documentation](https://webpack.js.org/plugins/commons-chunk-plugin/#explicit-vendor-chunk)
 * **externals** object syntax externals [Webpack externals documentation](https://webpack.js.org/configuration/externals/#object)
 * **alias** object syntax resolve.alias [Webpack resolve.alias documentation](https://webpack.js.org/configuration/resolve/#resolve-alias)
 

### Allow multiple `webpack-yii2.json`
 
If your project needs multiple webpack configurations (take care of `manifest.js` collision), you can create the `webpack-yii2.json` directly in the assets directory.
 
#### Example

Instead of having `webpack-yii2.json` in root directory, you can put it in your assets directory.
 
In this case, application structure should look like this:
 
  * *index.php*
  * *composer.json*
  * *package.json*
  * *webpack-yii2.json*
  * *webpack.config.js*
  * *tsconfig.json*
  * **protected**
    * *yii* (console script)
    * **assets**
      * *WebpackAsset.php*
      * **webpack**
        * *webpack-yii2.json* -> **Webpack specific file**
        * *assets-catalog.json* -> generated by webpack
        * **dist** -> generated by webpack
          * **js**
            * *js bundles*
          * **css**
            * *css bundles*
        * **src**
          * *app.ts*
          * **css**
            * *app.css*
    * *...*      

In order to run this specific configuration,

For development run

```
webpack --env.config=protected/assets/webpack
```
or to enable automatic build on file change
```
webpack --watch  --env.config=protected/assets/webpack
```

For production run

```
webpack -p  --env.config=protected/assets/webpack
```

This will take the `webpack-yii2.json` which is in `protected/assets/webpack` everything else is unchanged
 
Contributing
------------

All code contributions - including those of people having commit access -
must go through a pull request and approved by a core developer before being
merged. This is to ensure proper review of all the code.

Fork the project, create a [feature branch ](http://nvie.com/posts/a-successful-git-branching-model/), and send us a pull request.
