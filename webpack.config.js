const Encore = require('@symfony/webpack-encore')
const path   = require('path')

const basePath = path.resolve(__dirname, './');
const assetsPath = path.join(basePath, './src/Resources/private')
const outputPath = path.join(basePath, './src/Resources/public')
const publicPath = 'bundles/asdoriasyliuspickuppointplugin'

const cssPath = path.join(assetsPath, './css')
const jsPath  = path.join(assetsPath, './js')

Encore
    .cleanupOutputBeforeBuild()
    .setOutputPath(outputPath)
    .setPublicPath('/' + publicPath)
    .setManifestKeyPrefix(publicPath)

    .addEntry('asdoria-pickup-point', [
        path.join(jsPath, './app.js'),
        path.join(cssPath, './app.scss'),
    ])

    .enableSassLoader()

    // .disableSingleRuntimeChunk()

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    // .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app

    .enableSourceMaps(!Encore.isProduction())

    .disableSingleRuntimeChunk()

    .enableVersioning(Encore.isProduction())

    .configureFilenames({
        js: 'js/[name].min.js',
        css: 'css/[name].min.css',
    })
;

const config = Encore.getWebpackConfig()

// config.watchOptions = {
//     poll: true,
//     ignored: /node_modules/
// }
// export the final configuration
module.exports      = config
