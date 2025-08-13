<?php

	namespace QueryLogger\Services;

	use QueryLogger\Contracts\QueryLoggerInterface;
	use QueryLogger\Exceptions\QueryLoggerException;
	use QueryLogger\Helpers\QueryFormatter;
	use QueryLogger\Helpers\StorageManager;
	use QueryLogger\Models\QueryLog;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Auth;

	class QueryLoggerService implements QueryLoggerInterface
	{
		protected $config;
		protected $storageManager;
		protected $queryFormatter;
		protected $queryCount = 0;
		protected $currentLogFile;

		public function __construct(array $config)
		{
			$this->config = $config;
			$this->storageManager = new StorageManager($config);
			$this->queryFormatter = new QueryFormatter();
		}

		public function enableForFile(string $fileName): void
		{
			if (!$this->config['enabled']) {
				throw QueryLoggerException::loggingDisabled();
			}

			$this->currentLogFile = $this->storageManager->prepareLogFile($fileName);
			$this->registerQueryListener();
		}

        public function enableForRoute(): void
        {
            if (!$this->config['enabled']) {
                throw QueryLoggerException::loggingDisabled();
            }

            $routeName = 'non-http-context';

            if (app()->runningInConsole()) {
                $routeName = 'console-' . (request()->server('argv')[1] ?? 'artisan');
            }

            elseif ($route = request()->route()) {
                $routeName = $route->getName() ?: $route->uri();
            }


            $this->enableForFile($routeName);
        }

		public function enableForCommand(string $commandName): void
		{
			if (!$this->config['enabled']) {
				throw QueryLoggerException::loggingDisabled();
			}

			$this->enableForFile('cli-' . $commandName);
		}

		public function enableForJob(string $jobName): void
		{
			if (!$this->config['enabled']) {
				throw QueryLoggerException::loggingDisabled();
			}

			$this->enableForFile('job-' . $jobName);
		}

		public function setSlowQueryThreshold(int $threshold): void
		{
			if ($threshold < 0) {
				throw new \InvalidArgumentException('Slow query threshold must be a positive integer');
			}

			$this->config['slow_query_threshold'] = $threshold;
		}

		public function setLogFormat(string $format): void
		{
			$validFormats = ['plain', 'json'];

			if (!in_array($format, $validFormats)) {
				throw QueryLoggerException::invalidLogFormat($format, $validFormats);
			}

			$this->config['log_format'] = $format;
		}

		public function getCurrentLogFile(): ?string
		{
			return $this->currentLogFile;
		}

		public function getQueryCount(): int
		{
			return $this->queryCount;
		}

		protected function registerQueryListener(): void
		{
			DB::listen(function ($query) {
				if ($this->shouldSkipQuery($query)) {
					return;
				}
				$logEntry = $this->createLogEntry($query);
				$this->writeLogEntry($logEntry);
			});
		}

		protected function shouldSkipQuery($query): bool
		{
			return $this->config['slow_query_threshold'] > 0 &&
				$query->time < $this->config['slow_query_threshold'];
		}

		protected function createLogEntry($query): QueryLog
		{
			$this->queryCount++;
            $query_details = [
                'query_number' => $this->queryCount,
                'sql' => $this->queryFormatter->format($query->sql, $query->bindings),
                'execution_time' => $query->time,
                'timestamp' => now()->toDateTimeString(),
                'route' => request()->route() ? request()->route()->getName() : null,
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
                'user' => Auth::user() ? Auth::user()->email : null,

            ];

            if($this->getQuerySource()){
                $query_details = array_merge($query_details, [
                    'source' => $this->getQuerySource()
                ]);
            }

			return new QueryLog($query_details, $this->config);
		}

		protected function getQuerySource(): array
		{
			$sources = [];

            if(!$this->config['log_backtrace']){
                return $sources;
            }

			$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $this->config['backtrace_depth']);

			foreach ($backtrace as $trace) {
				if (isset($trace['file']) && isset($trace['line'])) {
					if (!$this->isVendorFile($trace['file'])) {
						$sources[] = [
							'file' => $trace['file'],
							'line' => $trace['line']
						];
					}
				}
			}

			if (empty($sources)) {
				throw QueryLoggerException::sourceNotDeterminable();
			}

			return $sources;
		}

		protected function isVendorFile(string $filePath): bool
		{
			return strpos($filePath, 'vendor') !== false ||
				strpos($filePath, 'Middleware') !== false ||
				strpos($filePath, 'public') !== false ||
				strpos($filePath, 'server') !== false;
		}

		protected function writeLogEntry(QueryLog $logEntry): void
		{
			$content = $this->config['log_format'] === 'json'
				? $logEntry->toJson()
				: $logEntry->toText();

			$this->storageManager->appendToLog($this->currentLogFile, $content);
		}
	}
