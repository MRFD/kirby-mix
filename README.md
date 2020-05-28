# Kirby 3 Mix

This plugin integrates the [Laravel Mix](https://laravel-mix.com/) manifest into [Kirby 3](https://getkirby.com), using the existing `js()` and `css()` helper functions.

## Requirements

- PHP 7.2+
- Kirby 3

## Installation

### Download

[Download](https://github.com/MRFD/kirby-mix/archive/master.zip) and copy the files to `/site/plugins/kirby-mix`.

### Git submodule

```bash
$ git submodule add https://github.com/MRFD/kirby-mix.git site/plugins/kirby-mix
```

### Composer

```
composer require MRFD/kirby-mix
```

## Usage

This plugin helps with the long-term caching that Laravel Mix provides with the `mix.version()` function. Read more about it in the Laravel Mix [documentation](https://laravel-mix.com/docs/master/versioning).

The plugin is enabled by default, and passes files trough that are not in the manifest. All functionality offered by the `js()` and `css()` helpers remains unchanged.

#### Usage example

```php
<?= css('assets/js/app.css') ?>

<?= js('assets/js/app.js') ?>
```

```html
<script src="https://domain.com/assets/js/app.js?id=c14116f0ac177cab618e"></script>

<link
  href="https://domain.com/assets/css/app.css?id=ffd6ebc479deb7f64dec"
  rel="stylesheet"
/>
```

#### Example webpack.mix.js

```js
const mix = require("laravel-mix");

mix
  .setPublicPath("assets")
  .setResourceRoot("../")
  .sass("resources/assets/css/app.scss", "js")
  .js("resources/assets/js/app.js", "css")
  .version();
```

## Options

| Option                | Default             | Description                                         |
| --------------------- | ------------------- | --------------------------------------------------- |
| `mrfd.mix.enable`     | `true`              | Activated or deactivated the Kirby Mix plugin.      |
| `mrfd.mix.manifest`   | `mix-manifest.json` | File name including extension of the manifest file. |
| `mrfd.mix.publicpath` | `assets`            | The public path to the assets folder.               |

## License

Kirby Mix is open-sourced software licensed under the MIT license.

Copyright © 2020 [Marijn Roovers](https://www.mrfd.nl)
