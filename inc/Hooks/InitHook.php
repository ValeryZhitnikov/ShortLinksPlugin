<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;
use ShortLinks\Services\RegisterMetaBoxes;
use ShortLinks\Services\RegisterColumns;
use ShortLinks\Services\RegisterActions;

class InitHook 
{
  public static function init(array $config): void {
    if ( ! empty( $config['entities'] ) ) {
      foreach ( $config['entities'] as $entityName ) {
        RegisterPostType::registerEntities($entityName);
        RegisterMetaBoxes::registerMetaboxes($entityName);
        RegisterColumns::registerColumns($entityName);
        RegisterActions::registerActions($entityName);
      }
    }
  }
}