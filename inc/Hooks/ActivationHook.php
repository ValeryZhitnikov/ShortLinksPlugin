<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;

class ActivationHook 
{
  public static function activate(array $config): void {
    if ( ! empty( $config['entities'] ) ) {
      RegisterPostType::registerEntities($config['entities']);
    }
    flush_rewrite_rules();
  }
}