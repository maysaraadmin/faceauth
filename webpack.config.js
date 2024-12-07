const path = require('path');

module.exports = {
    entry: {
        additionalSettings: './amd/src/additionalSettings.js',
        deletebtnjs: './amd/src/deletebtnjs.js',
        faceauth: './amd/src/faceauth.js',
        lightbox2: './amd/src/lightbox2.js',
        validateAdminUploadedImage: './amd/src/validateAdminUploadedImage.js',
        validateFace: './amd/src/validateFace.js',
        faceapi: 'face-api.js'
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, 'amd/build'),
        libraryTarget: 'amd'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    },
    devtool: 'source-map'
};