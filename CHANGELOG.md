# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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
