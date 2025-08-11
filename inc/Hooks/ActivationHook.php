<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;
use ShortLinks\Config;

class ActivationHook 
{
  public static function activate(): void {
    if ( ! empty( Config::getEntities() ) ) {
      foreach ( Config::getEntities() as $entityName ) {
        RegisterPostType::registerEntities($entityName);
      }
    }
    flush_rewrite_rules();
  }
}