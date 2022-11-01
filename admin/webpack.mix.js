let mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.js('admin/js/wsform-normalize_webhook.js', 'admin/dist/js')
    .sass('admin/scss/wsform-normalize_webhook.scss', 'admin/dist/css')
    .mergeManifest();
