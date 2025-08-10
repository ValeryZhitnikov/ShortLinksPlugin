<?php 

namespace ShortLinks;

use ShortLinks\Helpers\Singleton;
use ShortLinks\Hooks\ActivationHook;
use ShortLinks\Hooks\InitHook;
use ShortLinks\Shortcodes;

class Shortlink extends Singleton 
{
  private array $config;
  protected function __construct( array $config ) {
    $this->config = $config;
    $this->init();
  }

  private function init(): void {
    register_activation_hook(__FILE__, function (): void {
      ActivationHook::activate($this->config);
    });

    add_action( 'init', function (): void {
      InitHook::init($this->config);
      Shortcodes::register();
    });
    
  }
  
}