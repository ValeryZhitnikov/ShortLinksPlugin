<?php 

namespace ShortLinks\Entities\ShortLink;

class Columns
{
  public static function addColumns($columns): array {
    $columns['url'] = 'URL';
    $columns['target_url'] = 'Target URL';
    $columns['close'] = 'Close';
    $columns['close_date'] = 'Close date';
    $columns['click_count'] = 'Всего переходов';
    $columns['unique_click_count'] = 'Уникальные переходы';
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
        $click_count = (int) get_post_meta($post_id, '_click_count', true);
        echo $click_count;
        break;

      case 'unique_click_count':
        $unique_click_count = (int) get_post_meta($post_id, '_unique_click_count', true);
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