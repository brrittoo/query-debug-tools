<?php
	
	namespace Brrittoo\QueryLogger\Exceptions;
	
	use Exception;
	
	class QueryLoggerException extends Exception
	{
		/**
		 * Exception thrown when an invalid log format is provided
		 *
		 * @param string $format
		 * @param array $validFormats
		 * @return static
		 */
		public static function invalidLogFormat(string $format, array $validFormats = ['plain', 'json'])
		{
			return new static(
				sprintf(
					'Invalid log format "%s". Supported formats: %s',
					$format,
					implode(', ', $validFormats)
				)
			);
		}
		
		/**
		 * Exception thrown when logging is attempted but disabled
		 *
		 * @return static
		 */
		public static function loggingDisabled()
		{
			return new static('Query logging is currently disabled. Enable it in the configuration first.');
		}
		
		/**
		 * Exception thrown when storage directory is not writable
		 *
		 * @param string $path
		 * @return static
		 */
		public static function storageNotWritable(string $path)
		{
			return new static(
				sprintf('The storage directory "%s" is not writable.', $path)
			);
		}
		
		/**
		 * Exception thrown when a query source cannot be determined
		 *
		 * @return static
		 */
		public static function sourceNotDeterminable()
		{
			return new static('Could not determine the source of the query.');
		}
	}