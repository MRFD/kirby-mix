<?php

use Kirby\Cms\Url;

function mixManifest(Kirby $kirby): array
{
    $manifestFile = $kirby->root('index') . DS . option('mrfd.mix.publicpath') . DS . option('mrfd.mix.manifest');
    $manifest = [];

    if (file_exists($manifestFile)) {
        $manifest = json_decode(file_get_contents($manifestFile, true), true);
    }

    return $manifest;
}

function getFromManifest(Kirby $kirby, string $url): string
{
    $publicPath = option('mrfd.mix.publicpath');
    $url = str_replace($publicPath, '', Url::path($url, false));
    $manifest = mixManifest($kirby);

    return DS . $publicPath . ($manifest[$url] ?? $url);
}


Kirby::plugin('mrfd/mix', [
    'components' => [
        'css' => function (Kirby $kirby, string $url, $options = null): string {
            if (!option('mrfd.mix.enable')) {
                return $url;
            }

            return getFromManifest($kirby, $url);
        },
        'js' => function (Kirby $kirby, string $url, $options = null): string {
            if (!option('mrfd.mix.enable')) {
                return $url;
            }

            return getFromManifest($kirby, $url);
        }
    ],
    'options' => [
        'enable' => true,
        'manifest' => 'mix-manifest.json',
        'publicpath' => 'assets'
    ]
]);
