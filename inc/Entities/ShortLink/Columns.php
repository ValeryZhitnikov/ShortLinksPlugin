<?php

namespace ShortLinks\Entities\ShortLink;

use ShortLinks\Config;

/**
 * Class Columns
 *
 * Handles the registration, rendering, sorting, and ordering of custom columns
 * for the ShortLink entity in the WordPress admin list table.
 */
class Columns
{
  private const string URL = 'url';
  private const string TARGET_URL = 'target_url';
  private const string CLOSE = 'close';
  private const string CLOSE_DATE = 'close_date';
  private const string CLICK_COUNT = 'click_count';
  private const string UNIQUE_CLICK_COUNT  = 'unique_click_count';
  private const string CLOSE_ICON = '<span style="color: red;">&#10060;</span>';

  /**
   * Adds custom columns to the admin list table for the entity.
   *
   * @param array $columns Existing columns.
   * @return array Modified columns.
   */
  public static function addColumns($columns): array {
    $columns[self::URL]                = __('URL', Config::getTextDomain());
    $columns[self::TARGET_URL]         = __('Target URL', Config::getTextDomain());
    $columns[self::CLOSE]              = __('Close', Config::getTextDomain());
    $columns[self::CLOSE_DATE]         = __('Close date', Config::getTextDomain());
    $columns[self::CLICK_COUNT]        = __('All clicks', Config::getTextDomain());
    $columns[self::UNIQUE_CLICK_COUNT] = __('Unique clicks', Config::getTextDomain());
    return $columns;
  }

  /**
   * Fills the custom columns with data for each post.
   *
   * @param string $column Column key.
   * @param int $post_id Post ID.
   * @return void
   */
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

  /**
   * Makes certain columns sortable in the admin list table.
   *
   * @param array $columns Columns that are sortable.
   * @return array Modified sortable columns.
   */
  public static function sortableColumns(array $columns): array {
    $columns[self::CLICK_COUNT]        = self::CLICK_COUNT;
    $columns[self::UNIQUE_CLICK_COUNT] = self::UNIQUE_CLICK_COUNT;
    return $columns;
  }

  /**
   * Modifies the query to support sorting by custom meta fields.
   *
   * @param \WP_Query $query The current WP_Query instance.
   * @return void
   */
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
