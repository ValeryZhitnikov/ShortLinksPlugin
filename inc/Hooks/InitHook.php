<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;
use ShortLinks\Services\RegisterMetaBoxes;
use ShortLinks\Services\RegisterColumns;
use ShortLinks\Services\RegisterActions;
use ShortLinks\Config;

class InitHook 
{
  public static function init(): void {
    if ( ! empty( Config::getEntities() ) ) {
      foreach ( Config::getEntities() as $entityName ) {
        RegisterPostType::registerEntities($entityName);
        RegisterMetaBoxes::registerMetaboxes($entityName);
        RegisterColumns::registerColumns($entityName);
        RegisterActions::registerActions($entityName);
      }
    }
  }
}