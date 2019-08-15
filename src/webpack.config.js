const Encore = require('@symfony/webpack-encore');

const baseFolder = '/app/src';
const publicAssetsFolder = '/assets';

Encore.setOutputPath(`${baseFolder}/public/assets`)
  .setPublicPath(publicAssetsFolder)

  .addEntry('app', `${baseFolder}/assets/js/app.js`)
  .addEntry('posts', `${baseFolder}/assets/js/posts.js`)

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
    from: `${baseFolder}/assets/images`,
    to: 'assets/[path][mname].[hash:8].[ext]',
  })

  .enableReactPreset()
  .configureWatchOptions(() => ({
    poll: true,
    ignored: /node_modules/,
  }))

  // .enableEslintLoader() TODO: Activate this

  .enableIntegrityHashes(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
