<?php 

namespace ShortLinks\Entities\ShortLink;

class Columns
{
  public static function addColumns($columns): array {
    $columns['url'] = 'URL';
    $columns['target_url'] = 'Target URL';
    $columns['close'] = 'Close';
    $columns['close_date'] = 'Close date';
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
    }
  }
}