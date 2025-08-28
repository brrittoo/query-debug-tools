<?php
	
	namespace Brrittoo\QueryLogger\Contracts;
	
	interface QueryLoggerInterface
	{
		/**
		 * Enable query logging for a specific file
		 *
		 * @param string $fileName
		 * @return void
		 */
		public function enableForFile(string $fileName): void;
		
		/**
		 * Enable query logging for the current route
		 *
		 * @return void
		 */
		public function enableForRoute(): void;
		
		/**
		 * Enable query logging for an Artisan command
		 *
		 * @param string $commandName
		 * @return void
		 */
		public function enableForCommand(string $commandName): void;
		
		/**
		 * Enable query logging for a queued job
		 *
		 * @param string $jobName
		 * @return void
		 */
		public function enableForJob(string $jobName): void;
		
		/**
		 * Set the slow query threshold in milliseconds
		 *
		 * @param int $threshold
		 * @return void
		 */
		public function setSlowQueryThreshold(int $threshold): void;
		
		/**
		 * Set the log format (plain/json)
		 *
		 * @param string $format
		 * @return void
		 * @throws \QueryLogger\Exceptions\QueryLoggerException
		 */
		public function setLogFormat(string $format): void;
		
		/**
		 * Get the current log file path
		 *
		 * @return string|null
		 */
		public function getCurrentLogFile(): ?string;
		
		/**
		 * Get the query count for current session
		 *
		 * @return int
		 */
		public function getQueryCount(): int;
	}