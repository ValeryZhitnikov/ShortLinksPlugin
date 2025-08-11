<?php
namespace ShortLinks;

use ShortLinks\Config;

class OptionsPage
{
  private const string OPTIONS_PAGE_SLUG = 'shortlinks-settings';
  private const string SETTING_GROUP = 'shortlinks_settings_group';
  private const string SETTING_SECTION = 'shortlinks_main_section';
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
      self::OPTIONS_PAGE_SLUG,
      [self::class, 'renderSettingsPage']
    );
  }

  public static function registerSettings(): void
  {
    register_setting(self::SETTING_GROUP, Config::CLICK_TIME_OFFSET_OPTION, [
      'type'              => 'integer',
      'sanitize_callback' => 'absint',
      'default'           => Config::getUniqueClicksTimeOffset(),
    ]);

    add_settings_section(
      self::SETTING_SECTION,
      __('General Settings', Config::getTextDomain()),
      null,
      self::OPTIONS_PAGE_SLUG
    );

    add_settings_field(
      Config::CLICK_TIME_OFFSET_OPTION,
      __('Unique Click Time Offset (minutes)', Config::getTextDomain()),
      [self::class, 'clickOffsetField'],
      self::OPTIONS_PAGE_SLUG,
      self::SETTING_SECTION
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

    settings_fields(self::SETTING_GROUP);
    do_settings_sections(self::OPTIONS_PAGE_SLUG);
    submit_button();

    echo <<<HTML
      </form>
    </div>
    HTML;
  }
}
