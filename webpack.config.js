module.exports = {
    mode: "production",
    entry: {
        index: "./src/index.js",
        main: ["./src/api.js",
            "./src/control-sidebar.js",
            "./src/sidebar.js",
            "./src/tab-ajax.js",
            "./src/vue-gql.js",
            "./src/default.js",
            "./src/confirm.js",
            "./src/vue.dialog.js"]
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