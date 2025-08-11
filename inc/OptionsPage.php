<?php
namespace ShortLinks;

use ShortLinks\Config;

class OptionsPage
{
  public static function register(): void
  {
    add_action('admin_menu', [self::class, 'addMenuPage']);
    add_action('admin_init', [self::class, 'registerSettings']);
  }

  public static function addMenuPage(): void
  {
    add_options_page(
      __('Short Links Settings', Config::getTextDomain()),
      __('Short Links', Config::getTextDomain()),
      'manage_options',
      'shortlinks-settings',
      [self::class, 'renderSettingsPage']
    );
  }

  public static function registerSettings(): void
  {
    register_setting('shortlinks_settings_group', Config::CLICK_TIME_OFFSET_OPTION, [
      'type'              => 'integer',
      'sanitize_callback' => 'absint',
      'default'           => Config::getUniqueClicksTimeOffset(),
    ]);

    add_settings_section(
      'shortlinks_main_section',
      __('General Settings', Config::getTextDomain()),
      null,
      'shortlinks-settings'
    );

    add_settings_field(
      'shortlinks_click_offset',
      __('Unique Click Time Offset (minutes)', Config::getTextDomain()),
      [self::class, 'clickOffsetField'],
      'shortlinks-settings',
      'shortlinks_main_section'
    );
  }

  public static function clickOffsetField(): void
  {
    $value = Config::getUniqueClicksTimeOffset();
    $html = '<input type="number" name="%s" value="%s" min="0" />';
    echo sprintf($html,
                  Config::CLICK_TIME_OFFSET_OPTION,
                          esc_attr($value));
  }

  public static function renderSettingsPage(): void
  {
    $title = esc_html__('Short Links Settings', Config::getTextDomain());

    echo <<<HTML
    <div class="wrap">
      <h1>{$title}</h1>
      <form method="post" action="options.php">
    HTML;

    settings_fields('shortlinks_settings_group');
    do_settings_sections('shortlinks-settings');
    submit_button();

    echo <<<HTML
      </form>
    </div>
    HTML;
  }
}
