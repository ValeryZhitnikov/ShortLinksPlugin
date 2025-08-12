<?php

namespace ShortLinks\Services;

/**
 * Class RegisterColumns
 *
 * Responsible for registering custom columns and sorting behavior
 * for custom post types (entities) in the WordPress admin area.
 */
class RegisterColumns
{
  /**
   * Registers columns and sorting hooks for the given entity.
   *
   * It dynamically locates the entity's Columns and Constants classes
   * in the format `ShortLinks\Entities\{EntityName}\Columns` and 
   * `ShortLinks\Entities\{EntityName}\Constants`, verifies existence of required methods/constants, 
   * and hooks them into WordPress.
   *
   * @param string $entityName The name of the entity.
   * @return void
   */
  public static function registerColumns(string $entityName): void {
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

    if (method_exists($entityColumns, 'addColumns')) {
      add_filter("manage_{$label}_posts_columns", [$entityColumns, 'addColumns']);
    } else {
      error_log("Class $entityColumns must have a static addColumns() method.");
    }

    if (method_exists($entityColumns, 'fillColumns')) {
      add_action("manage_{$label}_posts_custom_column", [$entityColumns, 'fillColumns'], 10, 2);
    } else {
      error_log("Class $entityColumns must have a static fillColumns() method.");
    }

    if (method_exists($entityColumns, 'sortableColumns')) {
      add_filter("manage_edit-{$label}_sortable_columns", [$entityColumns, 'sortableColumns']);
    }

    if (method_exists($entityColumns, 'orderby')) {
      add_action('pre_get_posts', [$entityColumns, 'orderby']);
    }
  }
}
