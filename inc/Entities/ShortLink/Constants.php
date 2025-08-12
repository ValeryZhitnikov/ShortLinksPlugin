<?php

namespace ShortLinks\Entities\ShortLink;

/**
 * Class Constants
 *
 * Holds constant values used across the ShortLink entity,
 * such as labels, meta field keys, shortcode names, and formatting options.
 */
abstract class Constants {

  public const string ENTITY_LABEL = 'shortlink';
  public const string ENTITY_NAME = 'Short Links';
  public const string SINGULAR_NAME = 'Short Link';
  public const string MENU_ICON = 'dashicons-admin-links';
  public const string META_BOX_ID = 'short_link_meta';
  public const string TARGET_URL_META_FIELD = '_target_url';
  public const string CLOSE_META_FIELD = '_close_link';
  public const string CLOSE_META_VALUE = 'close';
  public const string TOTAL_CLICK_META_FIELD = '_click_count';
  public const string UNIQUE_CLICK_META_FIELD = '_unique_click_count';
  public const string CLOSE_DATE_META_FIELD = '_close_link_date';
  public const string CLOSE_DATE_SAVE_FORMAT = 'd.m.Y';
  public const string SHOW_LINK_SHORTCODE_NAME = self::ENTITY_LABEL . '_form';
  public const string SESSION_KEY_PREFIX = self::ENTITY_LABEL . '_last_click_';
  
}