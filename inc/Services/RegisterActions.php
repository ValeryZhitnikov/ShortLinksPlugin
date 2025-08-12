<?php 

namespace ShortLinks\Services;

/**
 * Class RegisterActions
 *
 * Dynamically registers actions for a given entity if a corresponding class exists.
 */
class RegisterActions
{
  /**
   * Registers static actions for the specified entity, if the corresponding class and method exist.
   *
   * Looks for a class in the format `ShortLinks\Entities\{EntityName}\Actions`
   * and calls its static `register()` method if available.
   *
   * @param string $entityName The name of the entity.
   *
   * @return void
   */
  public static function registerActions(string $entityName): void {
    $entityActions = "ShortLinks\\Entities\\$entityName\\Actions";
    
    if (class_exists($entityActions) && method_exists($entityActions, 'register')) {
      $entityActions::register();
    } else {
      error_log("Class $entityActions must have a static register() method.");
    }
  }
}
