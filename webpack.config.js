const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    mode: "production",
    entry: {
        index: "./src/index.js",
        main: [
            "./src/main.js",
            "./src/api.js",
            "./src/control-sidebar.js",
            "./src/sidebar.js",
            "./src/tab-ajax.js",
            "./src/default.js",
            "./src/confirm.js",
            "./src/vue.dialog.js",
            "./node_modules/x-html-bs4/dist/x-html-bs4.umd.js"]
    },
    module: {
        rules: [
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
                use: [MiniCssExtractPlugin.loader, 'css-loader']
            }
        ]
    },
    plugins: [new MiniCssExtractPlugin()]
};