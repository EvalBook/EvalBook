

// Encore config.
var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // JS.
    .addEntry('modalDialogs', './assets/js/modal-dialogs.js')
    .addEntry('controls', './assets/js/controls.js')
    .addEntry('api', './assets/js/api.js')

    // CSS.
    .addStyleEntry('theme', './assets/css/theme.css')
    .addStyleEntry('dialogs', './assets/css/dialogs.css')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

let config = Encore.getWebpackConfig();
config.resolve.mainFields = ['browser', 'module', 'main'];
config.resolve.extensions = ['.mjs', '.js'];

// File watcher
config.watch = true;
config.watchOptions =  {
    aggregateTimeout: 1000,
};

module.exports = config;