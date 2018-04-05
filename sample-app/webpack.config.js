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

const prodFlag = (process.argv.indexOf('-p') !== -1) || (process.argv.indexOf('production') !== -1);

let confPath = './webpack-yii2.json';
if(argv.env && argv.env.config) {
    confPath = path.join(__dirname, argv.env.config, 'webpack-yii2.json');
}
if(!fs.existsSync(confPath)) {
    throw 'Error: file "' + confPath + '" not found.';
}

let config = require(confPath);
if (argv.env && argv.env.config) {
    config.sourceDir = path.relative(__dirname, argv.env.config);
}

module.exports = {
    entry: config.entry,
    context: path.resolve(__dirname, config.sourceDir, config.subDirectories.sources),
    output: {
        path: path.resolve(__dirname, config.sourceDir, config.subDirectories.dist),
        filename: prodFlag ?  config.assets.scripts + '/[name].[chunkhash:6].js' : config.assets.scripts + '/[name].js'
    },
    plugins: [
        new ExtractTextWebpackPlugin({
            filename:  function(getPath) {
                return getPath(prodFlag ? config.assets.styles + '/[name].[contenthash:6].css' : config.assets.styles + '/[name].css');
            },
            allChunks: true
        }),
        new CompressionWebpackPlugin({
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
                                if (config.hasOwnProperty('sri') === true && config.sri !== false) {
                                    let currentAsset = assets[i][j];
                                    if ((typeof currentAsset !== 'string') && (currentAsset.file)) {
                                        currentAsset = currentAsset.file;
                                    }
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
            },
            {
                test: /\.html$/,
                use: 'html-loader'
            }
        ]
    },
    optimization: {
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
    devtool: 'source-map',
    target: 'web'
};
