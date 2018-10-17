const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const extractSass = new ExtractTextPlugin('css/[name].css', {
    allChunks: true,
});

const scssUse = ExtractTextPlugin.extract({use: ['css-loader', 'postcss-loader', 'sass-loader']});

const base = {
    entry: {
        //    main: './source/frontend/main.js',
        style: './theme/scss/style.scss',
        //backend: './source/gallery-backend/main.js'
    },
    output: {
        path: path.resolve('./public/theme'),
        filename: "js/[name].js",
        publicPath: "http://0.0.0.0:8080/theme/",
    },
    resolve: {
        modules: ['node_modules'],
    },
    plugins: [],
    module: {
        rules: [
            {
                test: /\.scss$/,
                enforce: 'pre',
                loader: 'import-glob-loader'
            },
            {
                test: /\.js$/,
                include: [
                    path.resolve(__dirname, 'theme/js'),
                ],
                exclude: /(node_modules|bower_components)/,
                use: [
                    {
                        loader: 'babel-loader',
                        query: {
                            cacheDirectory: true
                        }
                    }
                ],
            },
            {
                test: /\.vue$/,
                use: [
                    {
                        loader: 'vue-loader',
                        options: {
                            loaders: {
                                js: 'babel-loader'
                            }
                        }
                    }
                ]
            },
            {
                test: /\.scss$/,
                use: scssUse
            }
        ]
    }
};


base.plugins.push(extractSass);


module.exports = base;
