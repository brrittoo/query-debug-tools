<?php
	
	namespace Brrittoo\QueryLogger\Helpers;
	
	use Illuminate\Support\Facades\File;
	use Carbon\Carbon;
	
	class StorageManager
	{
		protected $config;
		protected $basePath;
		
		public function __construct(array $config)
		{
			$this->config = $config;
			$this->basePath = $config['storage_path'] ?? storage_path('logs/querylog');
			$this->ensureDirectoryExists();
		}
		
		public function prepareLogFile(string $baseName): string
		{
			$date = now()->format('Y-m-d');
			$time = now()->format('H-i-s');
			$directory = $this->basePath . '/' . $date;
			
			$this->ensureDirectoryExists($directory);
			$this->cleanOldLogs();
			
			// Check config
			$multipleFiles = config('querylogger.enable_generate_multiple_files_in_same_file', true);
			
			if ($multipleFiles) {
				// Each run = new file with timestamp
				$fileName = "{$baseName}-{$date}-{$time}.log";
			} else {
				// Single file for the day (append logs)
				$fileName = "{$baseName}-{$date}.log";
			}
			
			return $directory . '/' . $fileName;
		}

		
		public function appendToLog(string $filePath, string $content): void
		{
			File::append($filePath, $content . PHP_EOL);
		}
		
		public function cleanOldLogs(): void
		{
			if ($this->config['retention_days'] <= 0) {
				return;
			}
			
			$cutoffDate = Carbon::now()->subDays($this->config['retention_days']);
			$directories = File::directories($this->basePath);
			
			foreach ($directories as $directory) {
				$dirDate = Carbon::createFromFormat('Y-m-d', basename($directory));
				if ($dirDate->lessThan($cutoffDate)) {
					File::deleteDirectory($directory);
				}
			}
		}
		
		protected function ensureDirectoryExists(string $path = null): void
		{
			$path = $path ?? $this->basePath;
			
			if (!File::exists($path)) {
				File::makeDirectory($path, 0755, true);
			}
		}
	}