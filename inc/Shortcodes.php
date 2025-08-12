<?php 

namespace ShortLinks;

use ShortLinks\Entities\ShortLink\Constants as ShortLinkConstants;
use ShortLinks\Config;

/**
 * Class Shortcodes
 *
 * Handles registration and rendering of custom shortcodes.
 */
class Shortcodes 
{
  /**
   * Register shortcodes.
   *
   * @return void
   */
  public static function register(): void {
    add_shortcode(
      ShortLinkConstants::SHOW_LINK_SHORTCODE_NAME,
      [self::class, 'showLink']
    );
  }

  /**
   * Renders the shortcode output for a given short link ID.
   *
   * Supports UTM parameters and optional custom link text.
   *
   * Example usage: [shortlink id="123" text="Click here" utm_source="newsletter"]
   *
   * @param array $atts Shortcode attributes. Expected:
   *                    - id (int): The ID of the short link post.
   *                    - text (string, optional): Custom link text.
   *                    - utm_* (string, optional): UTM parameters to append to the URL.
   *
   * @return string The rendered HTML output of the shortcode.
   */
  public static function showLink(array $atts): string {
    $templates = [
      'error'   => '<div class="shortlink-error">' . __('Error: invalid link ID provided.', Config::getTextDomain()) . '</div>',
      'closed'  => '<div class="shortlink-closed">' . __('Link deactivated since', Config::getTextDomain()) . ' %s. </div>',
      'general' => '<a href="%s">%s</a>',
    ];
    
    if (!isset($atts['id'])) {
      return $templates['error'];
    }

    $id = intval($atts['id']);
    $text = isset($atts['text']) ? sanitize_text_field($atts['text']) : '';

    if (!$id || get_post_type($id) !== ShortLinkConstants::ENTITY_LABEL) {
      return $templates['error'];
    }

    $isClosed = get_post_meta($id, ShortLinkConstants::CLOSE_META_FIELD, true);
    if ($isClosed) {
      $closedDate = get_post_meta($id, ShortLinkConstants::CLOSE_DATE_META_FIELD, true);
      return sprintf($templates['closed'], esc_html($closedDate));
    }

    $baseUrl = get_permalink($id);

    $utmParams = [];
    foreach ($atts as $key => $value) {
      if (strpos($key, 'utm_') === 0) {
        $utmParams[$key] = sanitize_text_field($value);
      }
    }

    if (!empty($utmParams)) {
      $baseUrl = add_query_arg($utmParams, $baseUrl);
    }

    $linkText = $text ? esc_html($text) : esc_url($baseUrl);

    return sprintf(
      $templates['general'], 
      esc_url($baseUrl),
      $linkText
    );
  }
}
