<?php 

namespace ShortLinks\Services;

class RegisterActions
{
  public static function registerActions( string $entityName ): void {
    $entityActions = "ShortLinks\\Entities\\$entityName\\Actions";
		if ( class_exists( $entityActions ) && method_exists( $entityActions, 'register' ) ) {
      $entityActions::register();
    } else {
      error_log( "Class $entityActions must have a static register() method." );
    }
	}
}