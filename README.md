# Query Logger for Laravel

[![Latest Version](https://img.shields.io/packagist/v/brrittoo/query-debug-tools.svg?style=flat-square)](https://packagist.org/packages/brrittoo/query-debug-tools)
[![Total Downloads](https://img.shields.io/packagist/dt/brrittoo/query-debug-tools.svg?style=flat-square)](https://packagist.org/packages/brrittoo/query-debug-tools)
[![License](https://img.shields.io/packagist/l/brrittoo/query-debug-tools.svg?style=flat-square)](https://packagist.org/packages/brrittoo/query-debug-tools)

Effortlessly log, monitor, and debug all database queries in your Laravel applications with detailed execution insights, source tracking, and customizable storag

---

## Features

- 📝 Comprehensive query logging with execution time tracking
- 🐌 Slow query detection with customizable threshold
- 📂 Configurable log storage and retention
- 🔍 Backtrace tracking to identify query origins
- 🖥️ Support for both web requests and console commands
- � Job/queue query logging support
- ⚙️ Flexible configuration options
- 📊 Multiple log formats (plain text or JSON)

---

## Installation

Install via Composer:

```bash
composer require brrittoo/query-debug-tools
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Brrittoo\QueryLogger\Providers\QueryLoggerServiceProvider" 
--tag="query-logger-config"
```

---

## Configuration

The published configuration is located at `config/querylogger.php`:

```php
    return [
        /*
        |--------------------------------------------------------------------------
        | Query Logger Enable
        |--------------------------------------------------------------------------
        |
        | Enable or disable query logging globally.
        |
        */
        'enabled' => env('QUERY_LOGGER_ENABLED', true),
        
        	    /*
		|--------------------------------------------------------------------------
		| Query Logger Enable
		|--------------------------------------------------------------------------
		|
		| Enable or disable query logging globally.
		|
		*/
	    'enable_generate_multiple_files_in_same_file' => env('QUERY_LOGGER_MULTIPLE_FILES', true),

        /*
        |--------------------------------------------------------------------------
        | Log Retention Days
        |--------------------------------------------------------------------------
        |
        | Number of days to keep log files before automatic deletion.
        | Set to 0 to disable automatic cleanup.
        |
        */
        'retention_days' => env('QUERY_LOGGER_RETENTION_DAYS', 15),

        /*
        |--------------------------------------------------------------------------
        | Storage Path
        |--------------------------------------------------------------------------
        |
        | Location where query log files will be stored.
        |
        */
        'storage_path' => storage_path('logs/querylog'),

        /*
        |--------------------------------------------------------------------------
        | Slow Query Threshold
        |--------------------------------------------------------------------------
        |
        | Only log queries slower than this threshold (in milliseconds).
        | Set to 0 to log all queries.
        |
        */
        'slow_query_threshold' => env('QUERY_LOGGER_SLOW_QUERY_THRESHOLD', 0),

        /*
        |--------------------------------------------------------------------------
        | Log Format
        |--------------------------------------------------------------------------
        |
        | Format of the log files: 'plain' or 'json'.
        |
        */
        'log_format' => env('QUERY_LOGGER_FORMAT', 'plain'),

        /*
        |--------------------------------------------------------------------------
        | Middleware Configuration
        |--------------------------------------------------------------------------
        |
        | Settings for automatic middleware registration.
        |
        */
        'middleware' => [
            /*
            |----------------------------------------------------------------------
            | Auto-Registered Middleware Groups
            |----------------------------------------------------------------------
            |
            | Middleware groups where the query logger should be automatically
            | registered (e.g., 'web', 'api').
            |
            */
            'enable_groups' => [
                'web',
                'api'
            ],

            /*
            |----------------------------------------------------------------------
            | Middleware Alias
            |----------------------------------------------------------------------
            |
            | The alias to use when registering the middleware.
            |
            */
            'alias' => 'query.logger',

            /*
            |----------------------------------------------------------------------
            | Excluded Routes
            |----------------------------------------------------------------------
            |
            | Route names that should be excluded from logging.
            |
            */
            'exclude_routes' => [
                'login',
                'health-check',
                'horizon.*',
                'telescope',
                'debugbar.*'
            ],
        ],
        /*
        |--------------------------------------------------------------------------
        | Backtrace
        |--------------------------------------------------------------------------
        |
        | Whether to include the query origin backtrace.
        |
        */
        'log_backtrace' => env('QUERY_LOGGER_LOG_BACKTRACE', true),

        /*
        |--------------------------------------------------------------------------
        | Max Backtrace Depth
        |--------------------------------------------------------------------------
        |
        | Maximum number of backtrace frames to log.
        |
        */
        'backtrace_depth' => env('QUERY_LOGGER_BACKTRACE_DEPTH', 5),
    ];

```

---

## Usage

## Configuration

Set these in your `.env`:

```dotenv
| Variable                            | Default | Description                                                                            |
| ----------------------------------- | ------- | -------------------------------------------------------------------------------------- |
| `QUERY_LOGGER_ENABLED`              | `true`  | Enable or disable query logging globally.                                              |
| `QUERY_LOGGER_MULTIPLE_FILES`       | `true`  | Generate multiple log files per day. Set `false` to append to a single daily log file. |
| `QUERY_LOGGER_RETENTION_DAYS`       | `15`    | Number of days to keep old logs; older logs will be deleted automatically.             |
| `QUERY_LOGGER_SLOW_QUERY_THRESHOLD` | `1000`  | Threshold in milliseconds to consider a query as slow.                                 |
| `QUERY_LOGGER_FORMAT`               | `plain` | Log format: `plain` or `json`.                                                         |
| `QUERY_LOGGER_LOG_BACKTRACE`        | `false` | Enable logging of backtrace for each query.                                            |
| `QUERY_LOGGER_BACKTRACE_DEPTH`      | `5`     | Depth of backtrace to log (only used if backtrace logging is enabled).                 |

```

### Middleware

Add `query.logger` middleware to your routes or groups if not using auto-registration:

```php
Route::middleware(['query.logger'])->group(function () {
    Route::get('/', fn() => 'Hello World');
});
```

### Manual Logging

Enable query logging anywhere:

```php
QueryLogger::generateQueryLog($file_name); 
```

### Command Logging

Enable query logging inside an Artisan command:

```php
QueryLogger::enableForCommand($this->getName());
```

### Job Logging

Enable query logging inside a queued job:

```php
QueryLogger::enableForJob(get_class($this));
```

---

## Log Format

- `plain` — human-readable logs
- `json` — structured logs for programmatic analysis

---

## Contributing

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/MyFeature`
3. Commit your changes: `git commit -m 'Add new feature'`
4. Push to the branch: `git push origin feature/MyFeature`
5. Submit a pull request

🔗 Repository Link: https://github.com/ataulgalib/query-debug
---

## License

This package is open-sourced under the [MIT license](LICENSE).

---

## Author

**Ataul Galib**  
Email: ataul.gonigalib@gmail.com
