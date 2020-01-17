/* eslint-disable no-undef */
const mix = require('laravel-mix')
const path = require('path')
const Clean = require('clean-webpack-plugin')

require('laravel-mix-purgecss')

mix.webpackConfig(() => {
  return {
    plugins: [
      new Clean(['public/css', 'public/js', 'public/images/vendor', 'public/fonts'], { verbose: true })
    ],
    module: {
      rules: [
        {
          test: /\.hbs$/,
          loader: 'handlebars-loader/index.js',
          query: {
            runtime: 'handlebars/dist/handlebars.runtime.js'
          }
        }
      ]
    }
  }
})

let state = (Mix.inProduction()) ? 'production' : 'development'
console.warn('Let\'s roll ' + state + ' mode!')

mix.sass('resources/sass/app.scss', 'public/css')
  .purgeCss({
    enabled: Mix.inProduction(),
    // fontFace: true,
    keyframes: true,
    globs: [
      path.join(__dirname, 'config/locales.php')
    ],
    extensions: ['hbs', 'html', 'js', 'php'],
    whitelist: [
      'top-nav-collapse', 'waves-ripple', 'modal-backdrop',
      'arrow', 'show', 'top-nav-collapse', 'collapsing', 'collapse', 'collapsed',
      'col-lg-6', 'col-lg-4',
      'low', 'moderate', 'heavy', 'congested', 'unknown',
      'spinner', 'spinner-icon', 'bar', 'peg'// NProgress
    ],
    whitelistPatterns: [
      /^tooltip-/, /^bs-tooltip-/, /^nprogress/
    ]
  })
  .options({
    postCss: [
      require('css-mqpacker')(),
      require('postcss-preset-env')({ stage: 4 }),
      require('postcss-flexbugs-fixes'),
      require('cssnano')({ preset: 'advanced' })
    ]
  })

mix.js('resources/js/app.js', 'public/js')
mix.copy('resources/js/custom-icons.js', 'public/js').version()

if (Mix.inProduction()) {
  mix.version()
}

mix.browserSync({
  proxy: 'helper.local'
})
