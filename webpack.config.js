const VueLoaderPlugin = require('vue-loader/lib/plugin')
const path = require('path');

module.exports = {
    mode: "production",
    watch: true,
    entry: {
        index: "./src/index.js",
        main: [
            "./src/main.js",
            "./src/control-sidebar.js",
            "./src/tab-ajax.js",
            "./src/confirm.js"],
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
                            publicPath: "/css/",
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