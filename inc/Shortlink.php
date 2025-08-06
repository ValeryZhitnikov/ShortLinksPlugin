<?php 

namespace ShortLinks;

use ShortLinks\Helpers\Singleton;
use ShortLinks\Hooks\ActivationHook;
use ShortLinks\Hooks\InitHook;

class Shortlink extends Singleton 
{

  protected function __construct()
  {
    $this->init();
  }

  private function init(): void
  {
      register_activation_hook(__FILE__, [ActivationHook::class, 'activate']);
      add_action('init', [InitHook::class, 'init']);
  }
  
}