<?php 

namespace ShortLinks\Entities\ShortLink;

use ShortLinks\Config;

class Columns
{
  public static function addColumns($columns): array {
    $columns['url'] = __('URL', Config::getTextDomain());
    $columns['target_url'] = __('Target URL', Config::getTextDomain());
    $columns['close'] = __('Close', Config::getTextDomain());
    $columns['close_date'] = __('Close date', Config::getTextDomain());
    $columns['click_count'] = __('All clicks', Config::getTextDomain());
    $columns['unique_click_count'] = __('Unique clicks', Config::getTextDomain());
	  return $columns;
  }

  public static function fillColumns($column, $post_id): void {
    switch ($column) {
      case 'url':
        echo get_permalink($post_id);
        break;

      case 'target_url':
        $target_url = get_post_meta($post_id, Constants::TARGET_URL_META_FIELD, true);
        echo esc_url($target_url);
        break;

      case 'close':
        $close = get_post_meta($post_id, Constants::CLOSE_META_FIELD, true);
        if ( $close ) {
          echo '<span style="color: red;">&#10060;</span>';
        }
        break;

      case 'close_date':
        $close_date = get_post_meta($post_id, Constants::CLOSE_DATE_META_FIELD, true);
        echo esc_html($close_date);
        break;

      case 'click_count':
        $click_count = (int) get_post_meta($post_id, Constants::TOTAL_CLICK_META_FIELD, true);
        echo $click_count;
        break;

      case 'unique_click_count':
        $unique_click_count = (int) get_post_meta($post_id, Constants::UNIQUE_CLICK_META_FIELD, true);
        echo $unique_click_count;
        break;
    }
  }

  public static function sortableColumns(array $columns): array {
    $columns['click_count'] = 'click_count';
    $columns['unique_click_count'] = 'unique_click_count';
    return $columns;
  }

  public static function orderby( $query ): void {
    if ( ! is_admin() ) {
      return;
    }

    $orderby = $query->get('orderby');
    $post_type = $query->get('post_type');

    if ( $post_type !== Constants::ENTITY_LABEL ) {
      return;
    }

    if ( in_array($orderby, ['click_count', 'unique_click_count'], true) ) {
      switch ( $orderby ) {
        case 'click_count':
          $meta_key = Constants::TOTAL_CLICK_META_FIELD;
          break;
        case 'unique_click_count':
          $meta_key = Constants::UNIQUE_CLICK_META_FIELD;
          break;
        default:
          $meta_key = Constants::TOTAL_CLICK_META_FIELD;
      }
      $query->set('meta_key', $meta_key);
      $query->set('orderby', 'meta_value_num');
    }
  }
}