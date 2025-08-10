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
      if ( is_singular(Constants::ENTITY_LABEL) ) {
        global $post;
        $post_id = $post->ID;
        $target_url = get_post_meta($post_id, Constants::TARGET_URL_META_FIELD, true);
        $close = get_post_meta($post_id, Constants::CLOSE_META_FIELD, true);
        $total_clicks = (int) get_post_meta($post_id, Constants::TOTAL_CLICK_META_FIELD, true);
        
        if ($target_url && ! $close) {
         
          $utm_params = array_filter($_GET, function($key) {
            return strpos($key, 'utm_') === 0;
          }, ARRAY_FILTER_USE_KEY);

          if (!empty($utm_params)) {
            $new_query = http_build_query($utm_params);
            $target_url .= '?' . $new_query;
          }

          if (!session_id()) {
            session_start();
          }

          update_post_meta($post_id, Constants::TOTAL_CLICK_META_FIELD, $total_clicks + 1);

          $session_key = Constants::SESSION_KEY_PREFIX . $post_id;

          $last_click_time = $_SESSION[$session_key] ?? 0;
          $now = time();

          if (($now - $last_click_time) > MINUTE_IN_SECONDS * 2) {
              $unique_clicks = (int) get_post_meta($post_id, Constants::UNIQUE_CLICK_META_FIELD, true);
              update_post_meta($post_id, Constants::UNIQUE_CLICK_META_FIELD, $unique_clicks + 1);

              $_SESSION[$session_key] = $now;
          }

          wp_redirect($target_url, 301);
          exit;
        } else {
          global $wp_query;
          $wp_query->set_404();
          status_header(404);
          nocache_headers();
          include(get_query_template('404'));
          exit;
        }
      }
    });
  }
}