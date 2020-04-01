# Changelog

# 1.2
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


# 1.1
### Added
- Frontend scaffolding
- HTTP helpers

### Changed
- CSRF mechanism
- XHR handling

### Removed
- HTTP helpers in Controller


# 1.0
Initial release
