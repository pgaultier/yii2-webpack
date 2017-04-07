<?php
/**
 * WebpackController.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.1.0
 * @link http://www.sweelix.net
 * @package sweelix\webpack\commands
 */

namespace sweelix\webpack\commands;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Json;
use Yii;

/**
 * Manage assets through Webpack2
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.1.0
 * @link http://www.sweelix.net
 * @package sweelix\webpack\commands
 * @since 1.0.0
 */
class WebpackController extends Controller
{

    /**
     * @var string alias to package.json template file
     */
    public $packageJson = '@sweelix/webpack/templates/package.json';

    /**
     * @var string alias to webpack-yii2.json template file
     */
    public $webpackConfigJson = '@sweelix/webpack/templates/webpack-yii2.json';

    /**
     * @var string alias to webpack.config.js template file
     */
    public $webpackConfigJs = '@sweelix/webpack/templates/webpack.config.js';

    /**
     * @var string alias to tsconfig.json template file
     */
    public $tsConfigJson = '@sweelix/webpack/templates/tsconfig.json';

    /**
     * @var string relative path to composer.json
     */
    protected $composerJsonPath;

    /**
     * @inheritdoc
     */
    public $defaultAction = 'init';

    /**
     * Initialize webpack for current project (webpack.config.js, package.json, webpack-yii2.json)
     *
     * @return int
     * @since 1.0.0
     */
    public function actionInit()
    {

        $this->generatePackageJson();
        $this->generateConfigJson();
        $this->generateWebpackConfigJs();
        $this->generateTsConfigJson();
        $this->stdout('You can now configure your webpack assets with:'."\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout("\t".' * '.pathinfo($this->webpackConfigJson, PATHINFO_BASENAME)."\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout("\t".' * '.pathinfo($this->packageJson, PATHINFO_BASENAME)."\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout('Install npm dependencies: npm install'."\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout('Build assets: webpack'."\n", Console::BOLD, Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Generate webpack.config.js file
     *
     * @return int
     * @since 1.0.0
     */
    public function actionGenerateWebpack()
    {
        $this->generateWebpackConfigJs();
        $this->stdout('Build assets: webpack'."\n", Console::BOLD, Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Generate package.json file
     *
     * @return int
     * @since 1.0.0
     */
    public function actionGeneratePackage()
    {
        $this->generatePackageJson();
        $this->stdout('Install npm dependencies: npm install'."\n", Console::BOLD, Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Generate webpack-yii2.json file
     *
     * @return int
     * @since 1.0.0
     */
    public function actionGenerateConfig()
    {
        $this->generateConfigJson();
        $this->stdout('Build assets with generated config: webpack'."\n", Console::BOLD, Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Generate tsconfig.json file
     *
     * @return int
     * @since 1.0.1
     */
    public function actionGenerateTypescriptConfig()
    {
        $this->generateTsConfigJson();
        $this->stdout('Build typescript assets with generated config: webpack'."\n", Console::BOLD, Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Prepare webpack.config.js file
     * @since 1.0.0
     */
    protected function generateWebpackConfigJs()
    {
        $this->stdout('Generating webpack.config.js'."\n", Console::FG_GREEN, Console::BOLD);
        $composerJsonPath = $this->findComposerJson();
        $webpackJs = file_get_contents(Yii::getAlias($this->webpackConfigJs));
        $filename = pathinfo($this->webpackConfigJs, PATHINFO_BASENAME);
        $webpackConfigJsonFile = $composerJsonPath . '/' . $filename;
        if (file_exists($webpackConfigJsonFile) === true) {
            $question = $this->ansiFormat('Overwrite '.$filename.' ?', Console::FG_RED, Console::BOLD);
            $status = $this->confirm($question);
            if ($status === true) {
                file_put_contents($webpackConfigJsonFile, $webpackJs);
            }
        } else {
            file_put_contents($webpackConfigJsonFile, $webpackJs);
        }

    }

    /**
     * Prepare tsconfig.json file
     * @since 1.0.1
     */
    protected function generateTsConfigJson()
    {
        $this->stdout('Generating tsconfig.json'."\n", Console::FG_GREEN, Console::BOLD);
        $composerJsonPath = $this->findComposerJson();
        $webpackJs = file_get_contents(Yii::getAlias($this->tsConfigJson));
        $filename = pathinfo($this->tsConfigJson, PATHINFO_BASENAME);
        $webpackConfigJsonFile = $composerJsonPath . '/' . $filename;
        if (file_exists($webpackConfigJsonFile) === true) {
            $question = $this->ansiFormat('Overwrite '.$filename.' ?', Console::FG_RED, Console::BOLD);
            $status = $this->confirm($question);
            if ($status === true) {
                file_put_contents($webpackConfigJsonFile, $webpackJs);
            }
        } else {
            file_put_contents($webpackConfigJsonFile, $webpackJs);
        }

    }

    /**
     * Prepare webpack config for yii
     * @since 1.0.0
     */
    protected function generateConfigJson()
    {
        $this->stdout('Generating webpack-yii2.json'."\n", Console::FG_GREEN, Console::BOLD);
        $composerJsonPath = $this->findComposerJson();

        $configJson = Json::decode(file_get_contents(Yii::getAlias($this->webpackConfigJson)));
        $sourceDir = $this->prompt('Webpack assets path relative to package.json file ?', [
            'required' => true
        ]);
        $configJson['sourceDir'] = $sourceDir;

        $sourceSubDir = $this->prompt('Webpack assets source directory ?', [
            'required' => true,
            'default' => $configJson['subDirectories']['sources'],
        ]);
        $configJson['subDirectories']['sources'] = $sourceSubDir;

        $distSubDir = $this->prompt('Webpack assets distribution directory ?', [
            'required' => true,
            'default' => $configJson['subDirectories']['dist'],
        ]);
        $configJson['subDirectories']['dist'] = $distSubDir;

        $jsSubDir = $this->prompt('Webpack assets distribution scripts directory ?', [
            'required' => true,
            'default' => $configJson['assets']['scripts'],
        ]);
        $configJson['assets']['scripts'] = $jsSubDir;

        $cssSubDir = $this->prompt('Webpack assets distribution styles directory ?', [
            'required' => true,
            'default' => $configJson['assets']['styles'],
        ]);
        $configJson['assets']['styles'] = $cssSubDir;

        $catalogFilename = $this->prompt('Webpack catalog filename ?', [
            'required' => true,
            'default' => $configJson['catalog'],
        ]);
        $configJson['catalog'] = $catalogFilename;

        $configJson['entry'] = (object)[];
        $configJson['externals'] = (object)[];
        $configJson['alias'] = (object)[];
        $filename = pathinfo($this->webpackConfigJson, PATHINFO_BASENAME);
        $webpackConfigJsonFile = $composerJsonPath . '/' . $filename;
        if (file_exists($webpackConfigJsonFile) === true) {
            $question = $this->ansiFormat('Overwrite '.$filename.' ?', Console::FG_RED, Console::BOLD);
            $status = $this->confirm($question);
            if ($status === true) {
                file_put_contents($webpackConfigJsonFile, Json::encode($configJson, JSON_PRETTY_PRINT));
            }
        } else {
            file_put_contents($webpackConfigJsonFile, Json::encode($configJson, JSON_PRETTY_PRINT));
        }

    }

    /**
     * Search composer.json
     * @return string
     * @since 1.0.0
     */
    protected function findComposerJson()
    {
        if ($this->composerJsonPath === null) {
            $options = [
                'required' => true,
                'validator' => function($input, &$error) {
                    if (file_exists($input) === false) {
                        $error = 'The composer.json file must exist!';
                        return false;
                    }
                    return true;
                }
            ];
            if (file_exists('composer.json') === true) {
                $options['default'] = 'composer.json';
            }
            $composerJson = $this->prompt('Relative path to composer.json ?', $options);
            $composerPath = pathinfo($composerJson, PATHINFO_DIRNAME);
            $this->composerJsonPath = $composerPath;
        }
        return $this->composerJsonPath;

    }

    /**
     * Search information from composer.json and generate package.json
     * @since 1.0.0
     */
    protected function generatePackageJson()
    {
        $this->stdout('Generating package.json'."\n", Console::FG_GREEN, Console::BOLD);
        $composerJsonPath = $this->findComposerJson();
        $composerData = Json::decode(file_get_contents($composerJsonPath.'/composer.json'));

        $packageJson = Json::decode(file_get_contents(Yii::getAlias($this->packageJson)));

        $options = [
            'required' => true,
        ];
        if (isset($composerData['name']) === true) {
            $options['default'] = str_replace('/', '-', $composerData['name']);
        }
        $appName = $this->prompt('Application name ?', $options);
        $packageJson['name'] = $appName;

        $options = [
            'required' => true,
        ];
        if (isset($composerData['authors'][0]) === true) {
            $name = [];
            if (isset($composerData['authors'][0]['name'])) {
                $name[] = $composerData['authors'][0]['name'];
            }
            if (isset($composerData['authors'][0]['email'])) {
                $name[] = '<'.$composerData['authors'][0]['email'].'>';
            }
            if (empty($name) === false) {
                $options['default'] = implode(' ', $name);
            }
        }
        $author = $this->prompt('Author ?', $options);
        $packageJson['author'] = $author;

        $options = [];
        if (isset($composerData['description']) === true) {
            $options['default'] = $composerData['description'];
        }
        $description = $this->prompt('Description ?', $options);
        $packageJson['description'] = $description;


        $options = [];
        if (isset($composerData['license']) === true) {
            $options['default'] = $composerData['license'];
        }
        $license = $this->prompt('License ?', $options);
        $packageJson['license'] = $license;

        $filename = pathinfo($this->packageJson, PATHINFO_BASENAME);
        $packageJsonFile = $composerJsonPath . '/' . $filename;
        if (file_exists($packageJsonFile) === true) {
            $question = $this->ansiFormat('Overwrite '.$filename.' ?', Console::FG_RED, Console::BOLD);
            $status = $this->confirm($question);
            if ($status === true) {
                file_put_contents($packageJsonFile, Json::encode($packageJson, JSON_PRETTY_PRINT));
            }
        } else {
            file_put_contents($packageJsonFile, Json::encode($packageJson, JSON_PRETTY_PRINT));
        }
    }
}
