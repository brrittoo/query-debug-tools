<?php
	
	namespace Brrittoo\QueryLogger\Facades;
	
	use Illuminate\Support\Facades\Facade;
	
	class QueryLogger extends Facade
	{
		protected static function getFacadeAccessor()
		{
			return 'querylogger';
		}
	}