<?php

namespace ShortLinks\Services;

class RegisterColumns
{
  public static function registerColumns( string $entityName ): void {
		$entityColumns = "ShortLinks\\Entities\\$entityName\\Columns";
    $entityConstants = "ShortLinks\\Entities\\$entityName\\Constants";

    if (!class_exists($entityColumns)) {
      return;
    }

    if (defined("$entityConstants::ENTITY_LABEL")) {
      $label = constant("$entityConstants::ENTITY_LABEL");
    } else {
      error_log("Constant ENTITY_LABEL not found in class $entityConstants");
      return;
    }

    if ( method_exists( $entityColumns, 'addColumns' ) ) {
      add_filter("manage_{$label}_posts_columns", [$entityColumns, 'addColumns']);
    } else {
      error_log( "Class $entityColumns must have a static addColumns() method." );
    }

    if ( method_exists( $entityColumns, 'fillColumns' ) ) {
      add_action("manage_{$label}_posts_custom_column", [$entityColumns, 'fillColumns'], 10, 2);
    } else {
      error_log( "Class $entityColumns must have a static fillColumns() method." );
    }
	}
}