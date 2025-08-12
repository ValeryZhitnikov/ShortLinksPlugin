<?php

namespace ShortLinks\Services;

/**
 * Class RegisterPostType
 *
 * Handles registration of custom post types (entities) by
 * dynamically invoking the static register method of the entity class.
 */
class RegisterPostType
{
  /**
   * Registers the custom post type for the given entity.
   *
   * Looks for a class in the format `ShortLinks\Entities\{EntityName}\Entity`
   * and calls its static `register()` method if available.
   *
   * @param string $entityName The name of the entity to register.
   *
   * @return void
   */
  public static function registerEntities(string $entityName): void {
    $entity = "ShortLinks\\Entities\\$entityName\\Entity";

    if (class_exists($entity) && method_exists($entity, 'register')) {
      $entity::register();
    } else {
      error_log("Class $entity must have a static register() method.");
    }
  }
}
