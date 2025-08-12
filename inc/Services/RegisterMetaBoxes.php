<?php

namespace ShortLinks\Services;

/**
 * Class RegisterMetaBoxes
 *
 * Handles the registration of meta boxes and saving meta box data
 * for custom entities in the WordPress admin.
 */
class RegisterMetaBoxes
{
  /**
   * Registers meta box hooks for the given entity.
   *
   * Dynamically loads the entity's Fields class in the format `ShortLinks\Entities\{EntityName}\Fields`
   * and hooks its static methods for adding and saving meta boxes.
   *
   * @param string $entityName The name of the entity.
   * @return void
   */
  public static function registerMetaboxes(string $entityName): void
  {
    $entity = "ShortLinks\\Entities\\$entityName\\Fields";

    if (!class_exists($entity)) {
      return;
    }

    if (method_exists($entity, 'addMetaBox')) {
      add_action('add_meta_boxes', [$entity, 'addMetaBox']);
    } else {
      error_log("Class $entity must have a static addMetaBox() method.");
    }

    if (method_exists($entity, 'saveMetaBox')) {
      add_action('save_post', [$entity, 'saveMetaBox']);
    } else {
      error_log("Class $entity must have a static saveMetaBox() method.");
    }
  }
}
