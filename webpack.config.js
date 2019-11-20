module.exports = {
    mode: "production",
    entry: {
        index: "./src/index.js",
        //login: "./src/login.js",
        //layout: "./src/layout.js"
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