<?php 

namespace ShortLinks\Entities\ShortLink;

use ShortLinks\Entities\ShortLink\Constants;

class Actions
{
  public static function register(): void {
    self::addRedirects();
  }
  public static function addRedirects(): void {
    add_action('template_redirect', function() {
      if (is_singular(Constants::ENTITY_LABEL)) {
        global $post;
        $target_url = get_post_meta($post->ID, Constants::TARGET_URL_META_FIELD, true);
        if ( $target_url ) {
          wp_redirect($target_url, 301);
          exit;
        }
      }
    });
  }
}