const Encore = require('@symfony/webpack-encore');

const baseFolder = '/app';
const publicAssetsFolder = '/assets';

Encore.setOutputPath(`${baseFolder}/public/assets`)
  .setPublicPath(publicAssetsFolder)

  .addEntry('app', `${baseFolder}/assets/js/app.js`)

  .splitEntryChunks()

  .enableSingleRuntimeChunk()

  .cleanupOutputBeforeBuild()

  .enableBuildNotifications()

  .enableSourceMaps(true)

  .enableVersioning(Encore.isProduction())

  .configureBabel(() => {}, {
    useBuiltIns: 'usage',
    corejs: 3,
  })

  .enableSassLoader()
  .copyFiles({
    from: '/app/assets/images',
    to: 'assets/[path][mname].[hash:8].[ext]',
  })

  .enableReactPreset()

  // .enableEslintLoader()

  .enableIntegrityHashes(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
