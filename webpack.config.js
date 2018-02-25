var path = require('path');
var webpack = require('webpack');
var glob = require("glob");
var CommonsChunkPlugin = require("webpack/lib/optimize/CommonsChunkPlugin");


module.exports = {
    entry: {
        'dashboard': './/resources/vue/admin/dashboard.js',
        'home': './/resources/vue/home/index.js',
    },
    output: { path: __dirname + '/front-end/resources/js/', filename: '[name]/[name].chunk.js' },
    module: {
        loaders: [
            {
                test: /.js?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        }
    },
    plugins: [
        new CommonsChunkPlugin("commons")
    ]
};