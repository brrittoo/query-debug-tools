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

            if($this->config['log_backtrace']){
                $text .= "Source:\n";
                foreach ($this->attributes['source'] as $source) {
                    $text .= "  - {$source['file']}:{$source['line']}\n";
                }
            }


			return $text;
		}

		public function toJson(): string
		{
			return json_encode($this->attributes, JSON_PRETTY_PRINT);
		}
	}
