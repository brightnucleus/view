# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [0.1.4] - 2016-10-21
### Added
- Templates are bound to the scope of the view.
- Rendering of partials is available from the template through `$this->renderPart()` method.

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

[0.1.4]: https://github.com/brightnucleus/view/compare/v0.1.3...v0.1.4
[0.1.3]: https://github.com/brightnucleus/view/compare/v0.1.2...v0.1.3
[0.1.2]: https://github.com/brightnucleus/view/compare/v0.1.1...v0.1.2
[0.1.1]: https://github.com/brightnucleus/view/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/brightnucleus/view/compare/v0.0.0...v0.1.0
