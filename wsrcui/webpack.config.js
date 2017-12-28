const path = require('path');
const webpack = require('webpack');

module.exports = {
    //context: path.resolve(__dirname, './wsrc'),
    context: path.resolve(__dirname),
    entry: {
        app: ['babel-polyfill', './wasm/Cmf/Web.js'],
    },
    output: {
        path: path.resolve(__dirname, './wasm/Cmf'),
        filename: '[name].bundle.js',
        // publicPath: '/assets', // Assets prefix
    },
    devServer: {
        contentBase: path.resolve(__dirname, './wasm/Cmf'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: [/node_modules/],
                use: [{
                    loader: 'babel-loader',
                    options: { 
                        presets: ['es2015', 'react', 'stage-0'],
                    },
                }],
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
        ],
    },
};