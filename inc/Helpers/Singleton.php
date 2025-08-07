<?php 

namespace ShortLinks\Helpers;
abstract class Singleton 
{
	private static array $instances = [];

	private function __construct() {
	}

	public static function get_instance(...$args): Singleton {
		if ( ! isset( self::$instances[ static::class ] ) ) {
			self::$instances[ static::class ] = new static(...$args);
		}

		return self::$instances[ static::class ];
	}
}