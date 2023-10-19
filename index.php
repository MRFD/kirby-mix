<?php

use Kirby\Cms\Url;
use Kirby\Data\Json;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Kirby\Filesystem\F;
use Kirby\Cms\App as Kirby;

function mixManifest(Kirby $kirby): array
{
    $manifestFile = $kirby->root('index') . '/' . option('mrfd.mix.publicPath') . '/' . option('mrfd.mix.manifest');
    $manifest = [];

    if ($file = F::read($manifestFile)) {
        $manifest = Json::decode($file);
    }

    return $manifest;
}

function getFromManifest(Kirby $kirby, string $url): string
{
    $publicPath = option('mrfd.mix.publicPath');
    $manifest = mixManifest($kirby);

    $file = Str::replace(Url::path($url, false), $publicPath, '');
    $fileVersion = A::get($manifest, $file, false);

    if ($fileVersion === false) {
        return $url;
    }

    return '/' . $publicPath . $fileVersion;
}

function isInternalUrl(Kirby $kirby, string $url): bool
{
    $url = Url::to($url);

    return \strpos($url, $kirby->site()->url()) !== false || \strpos($url, "/") === '0';
}


Kirby::plugin('mrfd/mix', [
    'components' => [
        'css' => function (Kirby $kirby, string $url, $options = null): string {
            if (option('mrfd.mix.enable') === false) {
                return $url;
            }

            if (isInternalUrl($kirby, $url) === false) {
                return $url;
            }

            return getFromManifest($kirby, $url);
        },
        'js' => function (Kirby $kirby, string $url, $options = null): string {
            if (option('mrfd.mix.enable') === false) {
                return $url;
            }

            if (isInternalUrl($kirby, $url) === false) {
                return $url;
            }

            return getFromManifest($kirby, $url);
        }
    ],
    'options' => [
        'enable' => true,
        'manifest' => 'mix-manifest.json',
        'publicPath' => 'assets'
    ]
]);
