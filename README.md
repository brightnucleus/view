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

The simplest way to use the `View` component is through its Facade: `BrightNucleus\Views`.

This Facade makes use of a basic `BaseView` View implementation as well as a basic `PHPEngine` Engine implementation. It can just be used as is and does not need additional configuration.  

### Adding Locations

You can add locations via the static `View::addLocation($location)` method. Each location needs to implement the `Location`. The `View` component comes with one location provider out of the box: `FilesystemLocation`.

Here's how to add a set of folders as a new location:

```PHP
<?php namespace View\Example;

use View\Example\App;
use BrightNucleus\View;
use BrightNucleus\View\Location\FilesystemLocation;

$folders = [
    App::ROOT_FOLDER . '/child-theme/templates',
    App::ROOT_FOLDER . '/parent-theme/templates',
    App::ROOT_FOLDER . '/plugin/templates',
];

foreach ($folders as $folder) {
    Views::addLocation(new FilesystemLocation($folder));
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
use BrightNucleus\Views;

echo Views::render('welcome-user', [ 'userId' => User::getCurrentId() ]);
```

### Context

From within the template that is being rendered, the context variables are available as properties.

As an example, for the view we rendered above, you could use `echo $this->userId;` from within the template to retrieve that specific piece of context data.

The context as a whole is available through the method `$this->getContext()`, which will return an associative array. 

> Keep in mind that no automatic escaping is taking place, the value of the context data is passed as-is.

### Sections

To render a different template as a section from within the template currently being rendered, you can use the `$this->section($view, $context, $type)` method.

This does basically the same thing as an external `render()` call of a `View` object, with the following differences:

* It reuses the parent's `ViewBuilder`, with the same rendering engine, and the same locations.
* If you don't provide a context, it defaults to the parent's context. 

Here's an example of how this works:

```PHP
<?php namespace View\Example;

// This is our template that is being rendered.

?><h1>Welcome screen for User with ID <?= $this->userId ?></h1>
<p>This is an example template to show the rendering of partials.</p>
<hr>
<?= $this->section('user-notifications') ?>
<hr>
<?= $this->section('user-dashboard') ?>
```

## Advanced Usage

For more advanced use cases, you'll want to provide custom classes for your Views or Engines.

### Instantiating A Custom ViewBuilder

To do this, you'll want to create your `ViewBuilder` object manually, instead of relying on the `Views` Facade. By instantiating it manually, you can provide a custom Config to map your classes.

Once, you've got a `ViewBuilder` instance, you can use the `addLocation($location)` method to add locations to scan for views and the `create($view, $type)` method for creating an actual View. This View can then be rendered through its `render($context)` method. 

```PHP
<?php namespace View\Example;

use BrightNucleus\Config\ConfigFactory;
use BrightNucleus\View\ViewBuilder;
use BrightNucleus\View\Location\FilesystemLocation;

// Fetch the Config from somewhere.
$config = ConfigFactory::create(__DIR__. '/config/views.php');

// Create a new instance of the ViewBuilder and add a location.
$viewBuilder = new ViewBuilder( $config );
$viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/views'));

// Create a new instance of a specific View.
$view = $viewBuilder->create('my-view');

// Render the view.
echo $view->render(['answer' => 42]);
```

### Configuration Schema

Here's an example for providing a custom Config. In this case, we want to replace the default classes with more awesome ones.

```PHP
<?php namespace View\Example;

$engineFinder = [
    'ClassName'  => AwesomeEngineFinder::class,
    'Engines'    => [
        'AwesomeEngine' => AwesomeEngine::class,
    ],
    'NullObject' => AwesomeNullEngine::class,
];

$viewFinder = [
    'ClassName'  => AwesomeViewFinder::class,
    'Views'      => [
        'AwesomeView' => AwesomeView::class,
    ],
    'NullObject' => AwesomeNullView::class,
];

return [
    'BrightNucleus' => [
        'View' => [
            'EngineFinder' => $engineFinder,
            'ViewFinder'   => $viewFinder,
        ],
    ],
];
```

Of course you don't need to override all of the classes, views or engines. If you only override specific keys, the rest will be taken from the default values.

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2016 Alain Schlesser, Bright Nucleus

This code is licensed under the [MIT License](LICENSE).
