<?php
namespace ShortLinks;

/**
 * Class Config
 *
 * Provides configuration values and utility methods for the plugin.
 */
class Config
{
  /**
   * List of entities
   * 
   * @var array
   */
  private const array ENTITIES = [
    'ShortLink'
  ];

  private const array BLOCKS = [
    'ShowShortLink'
  ];

  private const string TEXT_DOMAIN = 'short-links';
  private const int UNIC_CLICKS_TIME_OFFSET_DEFAULT = 2;
  public const string CLICK_TIME_OFFSET_OPTION = 'shortlinks_unic_clicks_time_offset';

  /**
   * Returns the list of registered entity names used by the plugin.
   *
   * @return array List of entity names.
   */
  public static function getEntities(): array
  {
    return self::ENTITIES;
  }

  /**
   * Returns the list of registered gutenberg blocks names used by the plugin.
   * 
   * @return array List of gutenberg blocks names.
   */
  public static function getBlocks(): array
  {
    return self::BLOCKS;
  }

  /**
   * Returns the unique click time offset (in minutes) from plugin settings.
   * Falls back to the default if not set.
   *
   * @return int Unique clicks time offset in minutes.
   */
  public static function getUniqueClicksTimeOffset(): int
  {
    return get_option(self::CLICK_TIME_OFFSET_OPTION, self::UNIC_CLICKS_TIME_OFFSET_DEFAULT);
  }

  /**
   * Returns the plugin's text domain used for translations.
   *
   * @return string The text domain string.
   */
  public static function getTextDomain(): string
  {
    return self::TEXT_DOMAIN;
  }
}
