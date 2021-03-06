# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [0.4.9] - 2019-06-10
### Fixed
- Make sure file names are exact matches and disallow prefixed versions.

## [0.4.8] - 2019-02-02
### Fixed
- Remove left-over debugging code.

## [0.4.7] - 2019-02-02
### Fixed
- Respect relative folders that are provided as criteria.

## [0.4.6] - 2019-01-28
### Changed
- Cache `$uri`, `$engine` and `$view` for a given view name in `ViewBuilder`.

## [0.4.5] - 2019-01-12
### Changed
- When adding custom context to a `section()` call, it is merged with the existing context instead of replacing it.

## [0.4.4] - 2019-01-11
### Added
- Add optional argument `$context = []` to `AbstractView` to preload views with context.
- Add `addToContext( $key, $value, $behavior )` to `View` to add to existing view context.
- Add `ViewTest` class. 

## [0.4.3] - 2019-01-10
### Added
- Allow for invokable objects to be add as properties to the context.

### Changed
- Make view file selection more rigid.
- Improve passing on existing ViewBuilder instances.

## [0.4.2] - 2019-01-04
### Changed
- Let `ViewBuilder::addLocation()` return the view builder instance for fluent interfaces.

## [0.4.1] - 2019-01-04
### Changed
- Always accept absolute paths to existing views, even without any locations being registered.

## [0.4.0] - 2017-07-27
### Added
- Add comments to default config file.

### Changed
- Bump PHP version minimum to PHP 7+
- Remove and ignore Composer lock file.
- Provide and use class constants for config keys.
- Make internal property names more obscure and provide a method to access the entire context.
- Rename `View::renderPart()` to `View::section()`.

### Fixed
- Adapt copyright notice.
- Rearrange folder locations in example code.

## [0.3.0] - 2017-02-11
### Fixed
- Fix namespace error in `ViewFinder` interface.

## [0.2.1] - 2017-02-08
### Fixed
- Fix potential warning in AbstractFinder.

## [0.2.0] - 2016-10-21
### Added
- Templates are bound to the scope of the view.
- Rendering of partials is available from the template through `$this->section()` method.
- Default config is merged with overrides.
- Completed documentation.

## [0.1.3] - 2016-05-30
### Added
- FilesystemLocation recursive tests added.

### Changed
- FilesystemLocation uses Symfony Finder to traverse folders recursively.

## [0.1.2] - 2016-05-30
### Added
- Locations unit tests added.

### Changed
- Locations are only added when they haven't been registered before.

## [0.1.1] - 2016-05-30
### Added
- Basic documentation added.
- FilesystemLocation unit tests added.

### Changed
- Lots of refactoring to make the code more consistent.
- Lots of docblock tweaks.

## [0.1.0] - 2016-05-28
### Added
- Initial release to GitHub.

[0.4.9]: https://github.com/brightnucleus/view/compare/v0.4.8...v0.4.9
[0.4.8]: https://github.com/brightnucleus/view/compare/v0.4.7...v0.4.8
[0.4.7]: https://github.com/brightnucleus/view/compare/v0.4.6...v0.4.7
[0.4.6]: https://github.com/brightnucleus/view/compare/v0.4.5...v0.4.6
[0.4.5]: https://github.com/brightnucleus/view/compare/v0.4.4...v0.4.5
[0.4.4]: https://github.com/brightnucleus/view/compare/v0.4.3...v0.4.4
[0.4.3]: https://github.com/brightnucleus/view/compare/v0.4.2...v0.4.3
[0.4.2]: https://github.com/brightnucleus/view/compare/v0.4.1...v0.4.2
[0.4.1]: https://github.com/brightnucleus/view/compare/v0.4.0...v0.4.1
[0.4.0]: https://github.com/brightnucleus/view/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/brightnucleus/view/compare/v0.2.2...v0.3.0
[0.2.2]: https://github.com/brightnucleus/view/compare/v0.2.1...v0.2.2
[0.2.1]: https://github.com/brightnucleus/view/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/brightnucleus/view/compare/v0.1.3...v0.2.0
[0.1.3]: https://github.com/brightnucleus/view/compare/v0.1.2...v0.1.3
[0.1.2]: https://github.com/brightnucleus/view/compare/v0.1.1...v0.1.2
[0.1.1]: https://github.com/brightnucleus/view/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/brightnucleus/view/compare/v0.0.0...v0.1.0
