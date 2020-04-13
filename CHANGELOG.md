# Changelog

## 1.5
### Added
- Default config and router
- Helpers `merge_config` and `load_config`
- ErrorController actions `controllerNotFound` and `actionNotFound`

### Fixed
- Check if headers are already sent
- Check controller / action exists before call them
- Show ViewException message instead of throw it


## 1.4
### Added
- New `debug` helper and class
- Headers in controller response
- A console for CLI scripts

### Changed
- The view `render` method returns the content instead of include it
- The command `php src/bin/keygen` is now `php console keygen`


## 1.3
### Added
- Retrieve model ID with method `getId()`
- Secure helpers
- "Remember me" token for auth

### Fixed
- Default router
- Model casts


## 1.2
### Added
- Load your own helpers (on /app/helpers.php)
- Default router for non-rewrited urls
- Model validation messages
- `request` method in controllers
- `getAttributes` method in views
- String helper `normalize_string_reverse`

### Changed
- Exception in Model stops execution
- Model validation for required and validated fields
- String helper `sanitize` accepts arrays
- View helper `assets` check only one time the asset manifest file

### Fixed
- Retrieving model attributes

### Removed
- String helper `sanitize_array`


## 1.1
### Added
- Frontend scaffolding
- HTTP helpers

### Changed
- CSRF mechanism
- XHR handling

### Removed
- HTTP helpers in Controller


## 1.0
Initial release
