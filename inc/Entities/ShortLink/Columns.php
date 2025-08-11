<?php

namespace ShortLinks\Entities\ShortLink;

use ShortLinks\Config;

class Columns
{
  private const string URL = 'url';
  private const string TARGET_URL = 'target_url';
  private const string CLOSE = 'close';
  private const string CLOSE_DATE = 'close_date';
  private const string CLICK_COUNT = 'click_count';
  private const string UNIQUE_CLICK_COUNT  = 'unique_click_count';
  private const string CLOSE_ICON = '<span style="color: red;">&#10060;</span>';

  public static function addColumns($columns): array {
    $columns[self::URL]                = __('URL', Config::getTextDomain());
    $columns[self::TARGET_URL]         = __('Target URL', Config::getTextDomain());
    $columns[self::CLOSE]              = __('Close', Config::getTextDomain());
    $columns[self::CLOSE_DATE]         = __('Close date', Config::getTextDomain());
    $columns[self::CLICK_COUNT]        = __('All clicks', Config::getTextDomain());
    $columns[self::UNIQUE_CLICK_COUNT] = __('Unique clicks', Config::getTextDomain());
    return $columns;
  }

  public static function fillColumns(string $column, int $post_id): void {
    switch ($column) {
      case self::URL:
        echo get_permalink($post_id);
        break;

      case self::TARGET_URL:
        $target_url = get_post_meta($post_id, Constants::TARGET_URL_META_FIELD, true);
        echo esc_url($target_url);
        break;

      case self::CLOSE:
        $close = get_post_meta($post_id, Constants::CLOSE_META_FIELD, true);
        if ($close) {
          echo self::CLOSE_ICON;
        }
        break;

      case self::CLOSE_DATE:
        $close_date = get_post_meta($post_id, Constants::CLOSE_DATE_META_FIELD, true);
        echo esc_html($close_date);
        break;

      case self::CLICK_COUNT:
        $click_count = (int) get_post_meta($post_id, Constants::TOTAL_CLICK_META_FIELD, true);
        echo $click_count;
        break;

      case self::UNIQUE_CLICK_COUNT:
        $unique_click_count = (int) get_post_meta($post_id, Constants::UNIQUE_CLICK_META_FIELD, true);
        echo $unique_click_count;
        break;
    }
  }

  public static function sortableColumns(array $columns): array {
    $columns[self::CLICK_COUNT]        = self::CLICK_COUNT;
    $columns[self::UNIQUE_CLICK_COUNT] = self::UNIQUE_CLICK_COUNT;
    return $columns;
  }

  public static function orderby(\WP_Query $query): void {
    if (!is_admin()) {
      return;
    }

    $orderby   = $query->get('orderby');
    $post_type = $query->get('post_type');

    if ($post_type !== Constants::ENTITY_LABEL) {
      return;
    }

    if (in_array($orderby, [self::CLICK_COUNT, self::UNIQUE_CLICK_COUNT], true)) {
      $meta_key = match ($orderby) {
        self::CLICK_COUNT        => Constants::TOTAL_CLICK_META_FIELD,
        self::UNIQUE_CLICK_COUNT => Constants::UNIQUE_CLICK_META_FIELD,
        default                  => Constants::TOTAL_CLICK_META_FIELD,
      };

      $query->set('meta_key', $meta_key);
      $query->set('orderby', 'meta_value_num');
    }
  }
}
