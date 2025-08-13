<?php
	
	namespace QueryLogger\Helpers;
	
	class QueryFormatter
	{
		public function format(string $sql, array $bindings): string
		{
			if (empty($bindings)) {
				return $sql;
			}
			
			$formattedSql = '';
			$parts = explode('?', $sql);
			
			foreach ($parts as $i => $part) {
				$formattedSql .= $part;
				
				if (isset($bindings[$i])) {
					$formattedSql .= $this->formatBinding($bindings[$i]);
				}
			}
			
			return $formattedSql;
		}
		
		protected function formatBinding($value): string
		{
			if (is_string($value)) {
				return "'" . addslashes($value) . "'";
			}
			
			if (is_object($value)) {
				return $this->formatObjectBinding($value);
			}
			
			if (is_bool($value)) {
				return $value ? '1' : '0';
			}
			
			if (is_null($value)) {
				return 'NULL';
			}
			
			return (string) $value;
		}
		
		protected function formatObjectBinding($object): string
		{
			if (method_exists($object, '__toString')) {
				return (string) $object;
			}
			
			return json_encode($object);
		}
	}