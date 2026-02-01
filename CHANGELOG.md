# Changelog

All notable changes to `taapi-php` will be documented in this file.

## [1.0.0] - 2026-02-01

### Added
- Initial release
- Full support for taapi.io API v1
- GET (Direct) request support with fluent interface
- POST (Bulk) request support for multiple indicators
- POST (Manual) request support for custom candle data
- Strongly-typed Enums for Exchanges, Indicators, and Intervals
- Custom Exception handling (ApiException, RateLimitException, InvalidArgumentException)
- Response DTOs (IndicatorResponse, BulkResponse)
- Laravel integration with Service Provider and Facade
- Comprehensive PHPDoc documentation
- Full test coverage with PHPUnit
- Support for PHP 8.1+
