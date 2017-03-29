/**
 * webpack.config.js
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.ibitux.com/license license
 * @version XXX
 * @link http://www.ibitux.com
 */

const argv = require('yargs').argv;
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');
const AssetsPlugin = require('assets-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const prodFlag = process.argv.indexOf('-p') !== -1;

var confPath = './webpack-yii2.json';
if(argv.env && argv.env.config) {
    confPath = path.join(__dirname, argv.env.config, 'webpack-yii2.json');
}
if(!fs.existsSync(confPath)) {
    throw 'Error: file "' + confPath + '" not found.';
}

var config = require(confPath);
if (argv.env && argv.env.config) {
    config.sourceDir = path.relative(__dirname, argv.env.config);
}

module.exports = {
    entry: config.entry,
    context: path.resolve(__dirname, config.sourceDir, config.subDirectories.sources),
    output: {
        path: path.resolve(__dirname, config.sourceDir, config.subDirectories.dist, config.assets.scripts),
        filename: prodFlag ?  '[name].[chunkhash:6].js' : '[name].js'
    },
    plugins: [
        new webpack.DefinePlugin({
            process: {
                env: {
                    NODE_ENV: prodFlag ? '"production"': '"development"',
                    DEBUG: !prodFlag
                }
            }
        }),
        new webpack.optimize.CommonsChunkPlugin({
            names: config.commonBundles
        }),
        new ExtractTextPlugin({
            filename:  function(getPath) {
                return getPath(prodFlag ? '../' + config.assets.styles + '/[name].[contenthash:6].css' : '../' + config.assets.styles + '/[name].css');
            },
            allChunks: true
        }),
        new CompressionPlugin({
            asset: "[path].gz[query]",
            algorithm: "gzip",
            test: /\.(js|css)$/
            // threshold: 10240,
            // minRatio: 0.8
        }),
        new CleanWebpackPlugin([config.subDirectories.dist], {
            root: path.resolve(__dirname, config.sourceDir),
            verbose: true,
            dry: false,
            exclude: []
        }),
        new AssetsPlugin({
            prettyPrint: true,
            filename: config.catalog,
            path:config.sourceDir,
            processOutput: function (assets) {
                var i;
                var j;
                for (i in assets) {
                    if(assets.hasOwnProperty(i)) {
                        for (j in assets[i]) {
                            if (assets[i].hasOwnProperty(j)) {
                                assets[i][j] = path.join(config.assets.scripts, assets[i][j]).replace('\\', '/');
                            }
                        }
                    }
                }
                return JSON.stringify(assets, null, this.prettyPrint ? 2 : null);
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
                loader: 'file-loader?name=../[path][name].[ext]'
            },
            {
                test: /\.(jpg|png|gif)$/,
                loader: 'file-loader?name=../[path][name].[ext]'
            },
            {
                test: /\.less$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: ['css-loader', 'less-loader']
                })
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: ['css-loader', 'sass-loader']
                })
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: ['css-loader']
                })
            }
        ]
    },
    resolve: {
        alias: config.alias,
        extensions: ['.tsx', '.ts', '.js']
    },
    devtool: 'source-map',
    target: 'web'
};
