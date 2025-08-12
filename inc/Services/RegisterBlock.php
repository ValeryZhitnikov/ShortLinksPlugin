<?php

namespace ShortLinks\Services;

use ShortLinks\Config;
use ShortLinks\OptionsPage;

class RegisterBlock
{
  /**
   * Registers a single Gutenberg block by name.
   *
   * @param string $blockName Name of the block class inside ShortLinks\Blocks namespace.
   * @return void
   */
  public static function registerBlock(string $blockName): void
  {
    if (!function_exists('acf_register_block_type')) {
      return;
    }

    $blockClass = "ShortLinks\\Blocks\\$blockName";

    if (!class_exists($blockClass)) {
      return;
    }

    if (!method_exists($blockClass, 'getBlockSettings') || !method_exists($blockClass, 'renderCallback')) {
      error_log("Block class $blockClass must implement getBlockSettings() and renderCallback().");
      return;
    }

    $settings = $blockClass::getBlockSettings();
    $settings['render_callback'] = [$blockClass, 'renderCallback'];

    acf_register_block_type($settings);

    if (method_exists($blockClass, 'registerFields')) {
      $blockClass::registerFields();
    }
  }

  /**
   * Displays admin notice if ACF Pro is missing.
   *
   * @return void
   */
  public static function adminNotice(): void
  {
    add_action('admin_notices', function () {
      $screen = get_current_screen();
      if ($screen && $screen->id === 'settings_page_' . OptionsPage::getSlug()) {
        if (!function_exists('acf_register_block_type')) {
          $html = <<<HTML
          <div class="notice notice-warning">
            <p>
              %s
            </p>
          </div>
          HTML;

          echo sprintf(
            $html,
            __('ACF Pro плагин не найден. Gutenberg-блок будет недоступен.', Config::getTextDomain())
          );
        }
      }
    });
  }
}
