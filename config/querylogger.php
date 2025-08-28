<?php

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
        'backtrace_depth' => env('QUERY_LOGGER_BACKTRACE_DEPTH', 20),
    ];
