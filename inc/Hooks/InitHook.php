<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;
use ShortLinks\Services\RegisterMetaBoxes;
use ShortLinks\Services\RegisterColumns;
use ShortLinks\Services\RegisterActions;
use ShortLinks\Config;

/**
 * Class InitHook
 *
 * Handles the initialization process by registering post types,
 * meta boxes, columns, and actions for all configured entities.
 */
class InitHook 
{
  /**
   * Initializes all entities by registering their components.
   *
   * Loops through all entities defined in the Config and
   * delegates registration to the respective service classes.
   *
   * @return void
   */
  public static function init(): void {
    if (!empty(Config::getEntities())) {
      foreach (Config::getEntities() as $entityName) {
        RegisterPostType::registerEntities($entityName);
        RegisterMetaBoxes::registerMetaboxes($entityName);
        RegisterColumns::registerColumns($entityName);
        RegisterActions::registerActions($entityName);
      }
    }
  }
}
