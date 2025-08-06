<?php

namespace ShortLinks\Hooks;

use ShortLinks\Services\RegisterPostType;

class InitHook 
{
  public static function init(): void
  {
    RegisterPostType::registerShortLinkType();
  }
}