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
    $close = get_post_meta($post->ID, Constants::CLOSE_META_FIELD, true);
    $close_date = get_post_meta($post->ID, Constants::CLOSE_DATE_META_FIELD, true);
    $close_date_info = $close_date ? 'Ссылка деактивирована - '. $close_date : '';
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
      <p><label for="%1\$s">Target URL:</label></p>
      <input type="url" id="%1\$s" name="%1\$s" value="%2\$s" style="width:100%%;" />
      <p><label for="%3\$s">Close</label></p>
      <input type="checkbox" id="%3\$s" name="%3\$s" %4\$s />%5\$s
      <p><strong>Общее количество переходов:</strong> %6\$d</p>
      <p><strong>Количество уникальных переходов:</strong> %7\$d</p>
      HTML;

    echo sprintf(
      $html,
      Constants::TARGET_URL_META_FIELD,
      esc_attr($target_url),
      Constants::CLOSE_META_FIELD,
      checked( 'close', $close, false ),
      $close_date_info,
      $total_clicks,
      $unique_clicks
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

    if (isset($_POST[Constants::CLOSE_META_FIELD])) {
      $value = $_POST[Constants::CLOSE_META_FIELD];
      update_post_meta($post_id, Constants::CLOSE_META_FIELD, $value);
    }

    if ( isset( $_POST[Constants::CLOSE_META_FIELD] ) && 'on' == $_POST[Constants::CLOSE_META_FIELD] ) {
      $date_current = date('d.m.Y');
      update_post_meta( $post_id, Constants::CLOSE_META_FIELD, 'close' );
      update_post_meta( $post_id, Constants::CLOSE_DATE_META_FIELD, $date_current );
    } else {
      delete_post_meta( $post_id, Constants::CLOSE_META_FIELD );
      delete_post_meta( $post_id, Constants::CLOSE_DATE_META_FIELD );
    }
  }
}