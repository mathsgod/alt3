const VueLoaderPlugin = require('vue-loader/lib/plugin')
const path = require('path');
//const ASSET_PATH = process.env.ASSET_PATH 
__webpack_public_path__ = process.env.ASSET_PATH;

module.exports = {
    mode: "production",
    watch: true,
    entry: {
        app: "./src/app.js",
        index: "./src/index.js",
        main: [
            "./src/control-sidebar.js",
            "./src/sidebar.js",
            "./src/tab-ajax.js",
            "./src/confirm.js",
            "./src/vue.dialog.js"],
        plugin: "./src/plugin.js",

    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }, {
                test: /\.css$/i,
                use: ['style-loader', 'css-loader'],
            }, {
                test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: './css/',
                            publicPath: "dist/css/",
                            postTransformPublicPath: function (p) {
                                return `__webpack_public_path__ + ${p}`;
                            }
                        }
                    }
                ]
            }
        ]
    }, plugins: [
        // make sure to include the plugin!
        new VueLoaderPlugin()

     
    ]

};