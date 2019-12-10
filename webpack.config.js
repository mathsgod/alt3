module.exports = {
    mode: "production",
    entry: {
        index: "./src/index.js",
        confirm: "./src/confirm.js",
        api: "./src/api.js",
        "vue.dialog": "./src/vue.dialog.js"
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
            }
        ]
    }
};