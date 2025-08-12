<?php

namespace ShortLinks\Entities\ShortLink;

use ShortLinks\Interfaces\RegisterFields;
use ShortLinks\Config;

/**
 * Class Fields
 *
 * Implements meta box registration, rendering, and saving for the ShortLink entity.
 */
class Fields implements RegisterFields
{
  /**
   * Registers the meta box for the ShortLink post type.
   *
   * @return void
   */
  public static function addMetaBox(): void {
    if (!current_user_can('edit_others_posts')) {
      return;
    }

    add_meta_box(
      Constants::META_BOX_ID,
      __('Link info', Config::getTextDomain()),
      [self::class, 'renderMetaBox'],
      Constants::ENTITY_LABEL,
      'normal',
      'default'
    );
  }

  /**
   * Renders the meta box HTML.
   *
   * Displays fields for target URL, link closing status, click statistics, and nonce field.
   *
   * @param \WP_Post $post The current post object.
   * @return void
   */
  public static function renderMetaBox($post): void {
    $target_url = get_post_meta($post->ID, Constants::TARGET_URL_META_FIELD, true);
    $close = get_post_meta($post->ID, Constants::CLOSE_META_FIELD, true);
    $close_date = get_post_meta($post->ID, Constants::CLOSE_DATE_META_FIELD, true);
    $close_date_info = $close_date ? __('Link deactivated', Config::getTextDomain()) . ' - ' . $close_date : '';
    $total_clicks = (int) get_post_meta($post->ID, Constants::TOTAL_CLICK_META_FIELD, true);
    $unique_clicks = (int) get_post_meta($post->ID, Constants::UNIQUE_CLICK_META_FIELD, true);

    $nonce_field = wp_nonce_field(
      Constants::ENTITY_LABEL . '_save_meta_box',
      Constants::ENTITY_LABEL . '_meta_box_nonce',
      true,
      false
    );

    $html = <<<HTML
      $nonce_field
      <p><label for="%1\$s">%8\$s</label></p>
      <input type="url" id="%1\$s" name="%1\$s" value="%2\$s" style="width:100%%;" />
      <p><label for="%3\$s">%9\$s</label></p>
      <input type="checkbox" id="%3\$s" name="%3\$s" %4\$s />%5\$s
      <p><strong>%10\$s:</strong> %6\$d</p>
      <p><strong>%11\$s:</strong> %7\$d</p>
    HTML;

    echo sprintf(
      $html,
      Constants::TARGET_URL_META_FIELD,
      esc_attr($target_url),
      Constants::CLOSE_META_FIELD,
      checked('close', $close, false),
      $close_date_info,
      $total_clicks,
      $unique_clicks,
      __('Target URL', Config::getTextDomain()),
      __('Close', Config::getTextDomain()),
      __('Total clicks', Config::getTextDomain()),
      __('Unique clicks', Config::getTextDomain())
    );
  }

  /**
   * Saves meta box data when the post is saved.
   *
   * Validates user permissions, nonce, and sanitizes inputs.
   *
   * @param int $post_id The ID of the post being saved.
   * @return void
   */
  public static function saveMetaBox($post_id): void {
    if (!current_user_can('edit_others_posts')) {
      return;
    }

    if (!isset($_POST[Constants::ENTITY_LABEL . '_meta_box_nonce']) || 
        !wp_verify_nonce($_POST[Constants::ENTITY_LABEL . '_meta_box_nonce'], Constants::ENTITY_LABEL . '_save_meta_box')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (isset($_POST[Constants::TARGET_URL_META_FIELD])) {
      $target_url = esc_url_raw($_POST[Constants::TARGET_URL_META_FIELD]);

      if (filter_var($target_url, FILTER_VALIDATE_URL)) {
        update_post_meta($post_id, Constants::TARGET_URL_META_FIELD, $target_url);
      }
    }

    if (isset($_POST[Constants::CLOSE_META_FIELD])) {
      $value = $_POST[Constants::CLOSE_META_FIELD];
      update_post_meta($post_id, Constants::CLOSE_META_FIELD, $value);
    }

    if (isset($_POST[Constants::CLOSE_META_FIELD]) && 'on' == $_POST[Constants::CLOSE_META_FIELD]) {
      $date_current = date(Constants::CLOSE_DATE_SAVE_FORMAT);
      update_post_meta($post_id, Constants::CLOSE_META_FIELD, Constants::CLOSE_META_VALUE);
      update_post_meta($post_id, Constants::CLOSE_DATE_META_FIELD, $date_current);
    } else {
      delete_post_meta($post_id, Constants::CLOSE_META_FIELD);
      delete_post_meta($post_id, Constants::CLOSE_DATE_META_FIELD);
    }
  }
}
