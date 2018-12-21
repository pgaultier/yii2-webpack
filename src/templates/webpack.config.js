/**
 * webpack.config.js
 *
 * @author Philippe Gaultier <pgaultier@redcat.io>
 * @copyright 2010-2018 Redcat
 * @license http://www.redcat.io/license license
 * @version XXX
 * @link http://www.redcat.io
 */

const argv = require('yargs').argv;
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');
const AssetsWebpackPlugin = require('assets-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CompressionWebpackPlugin = require('compression-webpack-plugin');
const ExtractTextWebpackPlugin = require('extract-text-webpack-plugin');
const Hashes = require('jshashes');

const prodFlag = (process.argv.indexOf('-p') !== -1) || (process.argv.indexOf('production') !== -1);

var confPath = './webpack-yii2.json';
if(argv.env && argv.env.config) {
    confPath = path.join(__dirname, argv.env.config, 'webpack-yii2.json');
}
if(!fs.existsSync(confPath)) {
    throw 'Error: file "' + confPath + '" not found.';
}
var version = '1.0.0';

var config = require(confPath);
if (argv.env && argv.env.config) {
    config.sourceDir = path.relative(__dirname, argv.env.config);
}

var webpackConfig = {
    entry: config.entry,
    context: path.resolve(__dirname, config.sourceDir, config.subDirectories.sources),
    output: {
        path: path.resolve(__dirname, config.sourceDir, config.subDirectories.dist),
        filename: prodFlag ?  config.assets.scripts + '/[name].[chunkhash:6].js' : config.assets.scripts + '/[name].js'
    },
    plugins: [
        new webpack.DefinePlugin({
            PRODUCTION: JSON.stringify(prodFlag),
            VERSION: JSON.stringify(prodFlag ? version : version + '-dev'),
        }),
        new ExtractTextWebpackPlugin({
            filename:  function(getPath) {
                return getPath(prodFlag ? config.assets.styles + '/[name].[hash:6].css' : config.assets.styles + '/[name].css');
            },
            allChunks: true
        }),
        new CompressionWebpackPlugin({
            asset: "[path].gz[query]",
            algorithm: "gzip",
            test: /\.(js|css)/,
            threshold: 10,
            minRatio: 1
        }),
        new CleanWebpackPlugin([config.subDirectories.dist], {
            root: path.resolve(__dirname, config.sourceDir),
            verbose: true,
            dry: false,
            exclude: []
        }),
        new AssetsWebpackPlugin({
            prettyPrint: true,
            filename: config.catalog,
            path:config.sourceDir,
            processOutput: function (assets) {
                let i;
                let j;
                let finalAsset = {};
                for (i in assets) {
                    if(assets.hasOwnProperty(i)) {
                        if (finalAsset.hasOwnProperty(i) === false) {
                            finalAsset[i] = {};
                        }
                        for (j in assets[i]) {
                            if (assets[i].hasOwnProperty(j)) {
                                let currentAsset = assets[i][j];
                                if (Array.isArray(currentAsset) === true) {
                                    for (let c = 0; c < currentAsset.length; c++) {
                                        if ((typeof currentAsset[c] !== 'string') && (currentAsset[c].file)) {
                                            currentAsset[c] = currentAsset[c].file;
                                        }
                                        if (config.hasOwnProperty('sri') === true && config.sri !== false) {
                                            let file = path.resolve(__dirname, config.sourceDir, config.subDirectories.dist, currentAsset[c]);
                                            let contents = fs.readFileSync(file).toString();
                                            let hash;
                                            switch (config.sri) {
                                                case 'sha256':
                                                    hash = 'sha256-' + new Hashes.SHA256().b64(contents);
                                                    break;
                                                case 'sha512':
                                                default:
                                                    hash = 'sha512-' + new Hashes.SHA512().b64(contents);
                                                    break;
                                            }

                                            finalAsset[i][j] = {
                                                file: currentAsset[c].replace('\\', '/'),
                                                integrity: hash
                                            };
                                        } else {
                                            finalAsset[i][j] = currentAsset[c].replace('\\', '/');
                                        }

                                    }
                                } else {
                                    if ((typeof currentAsset !== 'string') && (currentAsset.file)) {
                                        currentAsset = currentAsset.file;
                                    }
                                    if (config.hasOwnProperty('sri') === true && config.sri !== false) {
                                        let file = path.resolve(__dirname, config.sourceDir, config.subDirectories.dist, currentAsset);
                                        let contents = fs.readFileSync(file).toString();
                                        let hash;
                                        switch (config.sri) {
                                            case 'sha256':
                                                hash = 'sha256-' + new Hashes.SHA256().b64(contents);
                                                break;
                                            case 'sha512':
                                            default:
                                                hash = 'sha512-' + new Hashes.SHA512().b64(contents);
                                                break;
                                        }

                                        finalAsset[i][j] = {
                                            file: currentAsset.replace('\\', '/'),
                                            integrity: hash
                                        };
                                    } else {
                                        finalAsset[i][j] = currentAsset.replace('\\', '/');
                                    }
                                }
                            }
                        }
                    }
                }
                return JSON.stringify(finalAsset, null, this.prettyPrint ? 2 : null);
            }
        })
    ],
    externals: config.externals,
    module: {
        rules: [
            {
                enforce: 'pre',
                test: /\.js$/,
                loader: 'source-map-loader'
            },
            {
                enforce: 'pre',
                test: /\.tsx?$/,
                use: 'source-map-loader'
            },
            {
                test: /\.tsx?$/,
                loader: 'ts-loader',
                exclude: /node_modules/
            },
            {
                test: /\.(ttf|eot|svg|woff|woff2)(\?[a-z0-9]+)?$/,
                loader: 'file-loader',
                options: {
                    name: '[path][name].[ext]'
                }
            },
            {
                test: /\.(jpg|png|gif)$/,
                loader: 'file-loader',
                options: {
                    name: '[path][name].[ext]'
                }
            },
            {
                test: /\.s[ac]ss$/,
                use: ExtractTextWebpackPlugin.extract({
                    publicPath: '../',
                    fallback: 'style-loader',
                    use: ['css-loader', 'sass-loader']
                })
            },
            {
                test: /\.css$/,
                use: ExtractTextWebpackPlugin.extract({
                    publicPath: '../',
                    fallback: 'style-loader',
                    use: ['css-loader']
                })
            }
        ]
    },
    optimization: {
        runtimeChunk: {
            name: "manifest"
        },
        splitChunks: {
            cacheGroups: {
                commons: {
                    test: /[\\/]node_modules[\\/]/,
                    name: "vendor",
                    chunks: "all"
                }
            }
        }
    },
    resolve: {
        alias: config.alias,
        extensions: ['.tsx', '.ts', '.js']
    },
    target: 'web'
};

if (!prodFlag) {
    webpackConfig.devtool = 'source-map';

}

module.exports = webpackConfig;
