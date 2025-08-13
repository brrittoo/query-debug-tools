<?php
	
	namespace QueryLogger\Models;
	
	class QueryLog
	{
		protected $config;
		protected $attributes = [];
		
		public function __construct(array $attributes, array $config)
		{
			$this->attributes = $attributes;
			$this->config = $config;
		}
		
		public function toText(): string
		{
			$text = "Query #{$this->attributes['query_number']}:\n";
			$text .= "SQL: {$this->attributes['sql']}\n";
			$text .= "Execution Time: {$this->attributes['execution_time']}ms\n";
			$text .= "Timestamp: {$this->attributes['timestamp']}\n";
			
			if ($this->attributes['route']) {
				$text .= "Route: {$this->attributes['route']}\n";
			}
			
			$text .= "Method: {$this->attributes['method']}\n";
			$text .= "URL: {$this->attributes['url']}\n";
			$text .= "IP: {$this->attributes['ip']}\n";
			
			if ($this->attributes['user']) {
				$text .= "User: {$this->attributes['user']}\n";
			}
			
			if ($this->config['log_backtrace'] && !empty($this->attributes['source'])) {
				$text .= "Source:\n";
				foreach ($this->attributes['source'] as $source) {
					$text .= "  - File: {$source['file']}:{$source['line']}\n";
					
					if (isset($source['class'])) {
						$text .= "    Class: {$source['class']}{$source['type']}{$source['function']}\n";
					}
					
					if (!empty($source['args'])) {
						$text .= "    Args: " . $this->formatArgsForText($source['args']) . "\n";
					}
				}
			}
			
			return $text;
		}
		
		protected function formatArgsForText(array $args): string
		{
			return implode(', ', array_map(function ($arg) {
				if (is_object($arg)) {
					return get_class($arg);
				}
				if (is_array($arg)) {
					return 'Array[' . count($arg) . ']';
				}
				if (is_string($arg)) {
					return "'" . (strlen($arg) > 50 ? substr($arg, 0, 47) . '...' : $arg) . "'";
				}
				return var_export($arg, true);
			}, $args));
		}
		
		public function toJson(): string
		{
			return json_encode($this->attributes, JSON_PRETTY_PRINT);
		}
	}