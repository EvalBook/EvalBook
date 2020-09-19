// Encore config.
var Encore = require('@symfony/webpack-encore');
var dotenv = require('dotenv');

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
    .addEntry('search', './assets/js/search.js')
    .addEntry('activity', './assets/js/activity.js')
    .addEntry('modalSchoolReports', './assets/js/modalSchoolReport.js')
    .addEntry('controlsSchoolReports', './assets/js/controlsSchoolReports.js')
    .addEntry('userRoles', './assets/js/userRoles.js')

    // CSS.
    .addStyleEntry('theme', './assets/css/theme.css')
    .addStyleEntry('dialogs', './assets/css/dialogs.css')
    .addStyleEntry('notebook-style', './assets/css/notebook.css')
    .addStyleEntry('search-style', './assets/css/search.css')
    .addStyleEntry('schoolReport', './assets/css/schoolReports/theme.css')
    .addStyleEntry('schoolReportECSJ', './assets/css/schoolReports/themeELSJ.css')
    .addStyleEntry('schoolReportBase', './assets/css/schoolReports/schoolReportBase.css')

    // Copying images files.
    .copyFiles({
        from: './assets/images',
        pattern: /\.(png|ico|jpg)$/
    })

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

    // define the environment variables
    .configureDefinePlugin(options => {
        const env = dotenv.config();

        if (env.error) {
            throw env.error;
        }

        options['process.env'].APP_ENV = JSON.stringify(env.parsed.APP_ENV);
    })
;

let config = Encore.getWebpackConfig();
config.resolve.mainFields = ['browser', 'module', 'main'];
config.resolve.extensions = ['.mjs', '.js'];

if(process.env.APP_ENV === 'dev') {
    // File watcher
    config.watch = true;
    config.watchOptions = {
        aggregateTimeout: 1000,
    };
}

module.exports = config;