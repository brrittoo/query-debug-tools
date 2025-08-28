<?php

	namespace Brrittoo\QueryLogger\Middleware;

	use Closure;
	use Brrittoo\QueryLogger\Services\QueryLoggerService;
	use Illuminate\Http\Request;

	class QueryLoggerMiddleware
	{
		protected $queryLogger;

		public function __construct(QueryLoggerService $queryLogger)
		{
			$this->queryLogger = $queryLogger;
		}

		public function handle(Request $request, Closure $next)
		{
			if ($this->shouldSkipLogging($request)) {
				return $next($request);
			}

			$this->queryLogger->enableForRoute();

			return $next($request);
		}

		protected function shouldSkipLogging(Request $request): bool
		{
			$excludedRoutes = config('querylogger.middleware.exclude_routes', []);

			return $request->route() && in_array(
					$request->route()->getName(),
					$excludedRoutes
				);
		}
	}
