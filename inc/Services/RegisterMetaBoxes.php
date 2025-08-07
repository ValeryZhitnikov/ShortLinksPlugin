<?php

namespace ShortLinks\Services;

class RegisterMetaBoxes
{
  public static function registerMetaboxes( string $entityName ): void {
		$entity = "ShortLinks\\Entities\\$entityName\\Fields";
    if ( class_exists( $entity ) ) {
      return;
    }

    if ( method_exists( $entity, 'addMetaBox' ) ) {
      add_action('add_meta_boxes', [$entity, 'addMetaBox']);
    } else {
      error_log( "Class $entity must have a static addMetaBox() method." );
    }

    if ( method_exists( $entity, 'saveMetaBox' ) ) {
      add_action('save_post', [$entity, 'saveMetaBox']);
    } else {
      error_log( "Class $entity must have a static saveMetaBox() method." );
    }
	}
}