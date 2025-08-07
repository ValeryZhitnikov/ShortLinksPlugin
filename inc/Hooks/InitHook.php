<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;

class InitHook 
{
  public static function init(array $config): void {
    if ( ! empty( $config['entities'] ) ) {
      RegisterPostType::registerEntities($config['entities']);
    }
  }
}