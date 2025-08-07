<?php 

namespace ShortLinks\Entities\ShortLink;

class Columns
{
  public static function addColumns($columns): array {
    $columns['url'] = 'URL';
    $columns['target_url'] = 'Target URL';
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
    }
  }
}