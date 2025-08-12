<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;
use ShortLinks\Config;

/**
 * Class ActivationHook
 *
 * Handles tasks that should be performed during plugin activation,
 * such as registering post types and flushing rewrite rules.
 */
class ActivationHook 
{
  /**
   * Executes activation routines for all entities.
   *
   * Registers all entities' post types and flushes rewrite rules
   * to ensure custom post type URLs work correctly.
   *
   * @return void
   */
  public static function activate(): void {
    if (!empty(Config::getEntities())) {
      foreach (Config::getEntities() as $entityName) {
        RegisterPostType::registerEntities($entityName);
      }
    }
    flush_rewrite_rules();
  }
}
