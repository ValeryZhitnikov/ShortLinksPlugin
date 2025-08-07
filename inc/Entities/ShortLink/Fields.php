<?php 

namespace ShortLinks\Entities\ShortLink;
use ShortLinks\Interfaces\RegisterFields;

class Fields implements RegisterFields
{
  public static function addMetaBox(): void {
    if (!current_user_can('edit_others_posts')) {
      return;
    }

    add_meta_box(
      'short_link_meta',
      'Link info',
      [self::class, 'renderMetaBox'],
      Constants::ENTITY_LABEL,              
      'normal',
      'default'
    );
  }

  public static function renderMetaBox($post): void {
    $target_url = get_post_meta($post->ID, Constants::TARGET_URL_META_FIELD, true);

    $nonce_field = wp_nonce_field(
      Constants::ENTITY_LABEL . '_save_meta_box',
      Constants::ENTITY_LABEL . '_meta_box_nonce',
      true,
      false
    );

    $html = <<<HTML
      $nonce_field
      <label for="%1\$s">Target URL:</label><br />
      <input type="url" id="%1\$s" name="%1\$s" value="%2\$s" style="width:100%%;" />
      HTML;

    echo sprintf(
      $html,
      Constants::TARGET_URL_META_FIELD,
      esc_attr($target_url)
    );
  }

  public static function saveMetaBox($post_id): void {
    if (!current_user_can('edit_others_posts')) {
      return;
    }

    if (!isset($_POST[Constants::ENTITY_LABEL.'_meta_box_nonce']) || !wp_verify_nonce($_POST[Constants::ENTITY_LABEL.'_meta_box_nonce'], Constants::ENTITY_LABEL.'_save_meta_box')) {
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
  }
}