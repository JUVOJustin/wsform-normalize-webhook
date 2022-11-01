let mix = require('laravel-mix');
require('laravel-mix-purgecss');
require('laravel-mix-merge-manifest');

mix.js('frontend/js/wsform-normalize_webhook-public.js', 'frontend/dist/js')
   .sass('frontend/scss/wsform-normalize_webhook-public.scss', 'frontend/dist/css')
   .purgeCss({
      content: ['frontend/views/**/*.twig'],
      css: ['frontend/dist/**/*.css']
  })
  .mergeManifest();
