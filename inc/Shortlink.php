<?php 

namespace ShortLinks;

use ShortLinks\Helpers\Singleton;
use ShortLinks\Hooks\ActivationHook;
use ShortLinks\Hooks\InitHook;
use ShortLinks\Shortcodes;
use ShortLinks\OptionsPage;

/**
 * Class Shortlink
 *
 * Main plugin class that handles initialization and registration of hooks.
 */
class Shortlink extends Singleton 
{
  /**
   * Plugin configuration array.
   *
   * @var array
   */
  private array $config;

  /**
   * Shortlink constructor.
   *
   * Initializes the plugin.
   */
  protected function __construct() {
    $this->init();
  }

  /**
   * Initializes plugin hooks.
   *
   * Registers the activation hook and initializes components on the 'init' action.
   *
   * @return void
   */
  private function init(): void {
    register_activation_hook(__FILE__, function (): void {
      ActivationHook::activate();
    });

    add_action('init', function (): void {
      InitHook::init();
      Shortcodes::register();
      OptionsPage::register();
    });
  }
}
