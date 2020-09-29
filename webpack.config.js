module.exports = {
    mode: "production",
    watch: true,
    entry: {
        app: "./src/app.js",
        index: "./src/index.js",
        main: [
            "./src/api.js",
            "./src/control-sidebar.js",
            "./src/sidebar.js",
            "./src/tab-ajax.js",
            "./src/default.js",
            "./src/confirm.js",
            "./src/vue.dialog.js"],
        plugin: "./src/plugin.js"
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
                use: ['style-loader', 'css-loader'],
            },
        ]
    }

};