<?php

	namespace QueryLogger\Providers;

	use Illuminate\Support\ServiceProvider;
	use QueryLogger\Services\QueryLoggerService;
	use QueryLogger\Middleware\QueryLoggerMiddleware;

	class QueryLoggerServiceProvider extends ServiceProvider
	{
		public function register()
		{
			$this->mergeConfigFrom(
				__DIR__.'/../../config/querylogger.php', 'querylogger'
			);

			$this->app->singleton('querylogger', function ($app) {
				return new QueryLoggerService(config('querylogger'));
			});

			$this->app->singleton(QueryLoggerMiddleware::class, function ($app) {
				return new QueryLoggerMiddleware($app->make('querylogger'));
			});
		}

		public function boot()
		{
            if (config('querylogger.enabled')) {
                $this->registerMiddleware();
            }
			$this->publishes([
				__DIR__.'/../../config/querylogger.php' => config_path('querylogger.php'),
			], 'query-logger-config');


		}

        protected function registerMiddleware()
        {
            $router = $this->app['router'];
            $config = config('querylogger.middleware');
            $router->aliasMiddleware($config['alias'], QueryLoggerMiddleware::class);

            foreach ($config['enable_groups'] as $group) {
                if ($router->hasMiddlewareGroup($group)) {
                    $router->pushMiddlewareToGroup($group, QueryLoggerMiddleware::class);
                }
            }
        }
	}
