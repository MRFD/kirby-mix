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

function isInternalUrl(Kirby $kirby, string $url): bool
{
    $url = Url::to($url);

    return strpos($url, $kirby->site()->url()) !== false || strpos($url, "/") === '0';
}


Kirby::plugin('mrfd/mix', [
    'components' => [
        'css' => function (Kirby $kirby, string $url, $options = null): string {
            if (!option('mrfd.mix.enable')) {
                return $url;
            }

            if (!isInternalUrl($kirby, $url)) {
                return $url;
            }

            return getFromManifest($kirby, $url);
        },
        'js' => function (Kirby $kirby, string $url, $options = null): string {
            if (!option('mrfd.mix.enable')) {
                return $url;
            }

            if (!isInternalUrl($kirby, $url)) {
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
