<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;

class ActivationHook 
{
  public static function activate(): void
  {
    RegisterPostType::registerShortLinkType();
    flush_rewrite_rules();
  }
}