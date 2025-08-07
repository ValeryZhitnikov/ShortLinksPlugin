<?php

namespace ShortLinks\Services;

class RegisterPostType
{
  public static function registerEntities( string $entityName ): void {
    $entity = "ShortLinks\\Entities\\$entityName\\Entity";
		if ( class_exists( $entity ) && method_exists( $entity, 'register' ) ) {
      $entity::register();
    } else {
      error_log( "Class $entity must have a static register() method." );
    }
	}
}