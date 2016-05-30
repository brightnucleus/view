# Bright Nucleus View Component

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/brightnucleus/view.svg)](https://scrutinizer-ci.com/g/brightnucleus/view/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/brightnucleus/view.svg)](https://scrutinizer-ci.com/g/brightnucleus/view/?branch=master)
[![Build Status](https://img.shields.io/scrutinizer/build/g/brightnucleus/view.svg)](https://scrutinizer-ci.com/g/brightnucleus/view/build-status/master)
[![Codacy Badge](https://img.shields.io/codacy/a7370932c3ce45fca747ce2aaf415b16.svg)](https://www.codacy.com/app/BrightNucleus/view)
[![Code Climate](https://img.shields.io/codeclimate/github/brightnucleus/view.svg)](https://codeclimate.com/github/brightnucleus/view)

[![Latest Stable Version](https://img.shields.io/packagist/v/brightnucleus/view.svg)](https://packagist.org/packages/brightnucleus/view)
[![Total Downloads](https://img.shields.io/packagist/dt/brightnucleus/view.svg)](https://packagist.org/packages/brightnucleus/view)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/brightnucleus/view.svg)](https://packagist.org/packages/brightnucleus/view)
[![License](https://img.shields.io/packagist/l/brightnucleus/view.svg)](https://packagist.org/packages/brightnucleus/view)

This is a reusable View component that can provide different implementations (in separate, optional packages).

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
    * [Adding Locations](#adding-locations)
    * [Rendering A View](#rendering-a-view)
* [Advanced Usage](#advanced-usage)
* [Contributing](#contributing)
* [License](#license)

## Installation

The best way to use this component is through Composer:

```BASH
composer require brightnucleus/view
```

## Basic Usage

The simplest way to use the `View` component is through its Facade: `BrightNucleus\View`.

### Adding Locations

You can add locations via the static `View::addLocation($location)` method. Each location needs to implement the `LocationInterface`. The `View` component comes with one location provider out of the box: `FilesystemLocation`.

Here's how to add a set of folders as a new location:

```PHP
<?php namespace View\Example;

use View\Example\App;
use BrightNucleus\View;
use BrightNucleus\View\Location\FilesystemLocation;

$folders = [
    App::ROOT_FOLDER . '/plugin/templates',
    App::ROOT_FOLDER . '/child-theme/templates',
    App::ROOT_FOLDER . '/parent-theme/templates',
];

foreach ($folders as $folder) {
    View::addLocation(new FilesystemLocation($folder));
}
```

### Rendering A View

To render a view, you pass a view identifier to the static `View::render($view, $context, $type)` method.

The `$view` is a view identifier that would normally be a template file name, but can also be something else depending on your configured locations & engines.

The `$context` array you pass in will be extracted to be available to the template at the time it is rendered.

The `$type` argument allows you to inject a specific view/engine combination, instead of letting the `View` component find one on its own.

> Note: In order for a view to be effectively rendered, it needs to be found amongst one of the locations that were already registered.

Here's how to render a simple view:

```PHP
<?php namespace View\Example;

use View\Example\User;
use BrightNucleus\View;

echo View::render('welcome-user', [ 'userId' => User::getCurrentId() ]);
```

## Advanced Usage

> TODO

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2016 Alain Schlesser, Bright Nucleus

This code is licensed under the [MIT License](LICENSE).
