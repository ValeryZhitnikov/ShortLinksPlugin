<?php 

namespace ShortLinks\Blocks;

use ShortLinks\Interfaces\RegisterBlock;
use ShortLinks\Config;
use ShortLinks\Entities\ShortLink\Constants as ShortLinkConstants;

/**
 * Class ShowShortLink
 * 
 * Implements a Gutenberg block that renders a shortlink using ACF fields.
 * The block allows setting a shortlink post ID, optional link text, and additional shortcode attributes.
 * 
 * This class provides methods to get block settings for registration, render the block output,
 * and register ACF fields required by the block.
 */
class ShowShortLink implements RegisterBlock
{
  private const string BLOCK_NAME = 'shortlink-block';
  private const string BLOCK_CATEGORY = 'widgets';
  private const string BLOCK_ICON = 'admin-links';
  private const string FIELD_SHORTLINK_ID = 'shortlink_id';
  private const string FIELD_LINK_TEXT = 'link_text';
  private const string FIELD_ATTRS = 'attrs';

  /**
   * Returns the block registration settings array for acf_register_block_type.
   *
   * @return array Block settings including name, title, description, category, icon, and support features.
   */
  public static function getBlockSettings(): array {
    return [
      'name'        => self::BLOCK_NAME,
      'title'       => __('ShowShortlink', Config::getTextDomain()),
      'description' => __('Renders a shortlink', Config::getTextDomain()),
      'category'    => self::BLOCK_CATEGORY,
      'icon'        => self::BLOCK_ICON,
      'supports'    => ['align' => false],
    ];
  }

  /**
   * Callback to render the block content on the frontend.
   *
   * Retrieves ACF fields, builds the shortcode attribute string, and echoes the shortcode output.
   *
   * @return void
   */
  public static function renderCallback(): void {
    $linkId = get_field(self::FIELD_SHORTLINK_ID) ?? 0;
    $text = get_field(self::FIELD_LINK_TEXT) ?? '';
    $attrs = get_field(self::FIELD_ATTRS) ?? '';

    if (!$linkId) {
      return;
    }

    $atts = 'id=' . intval($linkId);

    if (!empty($text)) {
      $safeText = esc_attr($text);
      $atts .= ' text="' . $safeText . '"';
    }

    if ($attrs !== '') {
      $atts .= ' ' . trim($attrs);
    }

    echo do_shortcode('[' . ShortLinkConstants::SHOW_LINK_SHORTCODE_NAME . ' ' . $atts . ']');
  }

  /**
   * Registers ACF fields for the block.
   *
   * Defines local field group and fields used in the block editor for user input.
   *
   * @return void
   */
  public static function registerFields(): void{
    acf_add_local_field_group([
      'key'    => 'group_shortlink_block',
      'title'  => __('Shortlink Block', Config::getTextDomain()),
      'fields' => [
        [
          'key'   => 'field_shortlink_id',
          'label' => __('Shortlink ID', Config::getTextDomain()),
          'name'  => self::FIELD_SHORTLINK_ID,
          'type'  => 'number',
          'required' => 1,
          'min' => 1,
        ],
        [
          'key'   => 'field_link_text',
          'label' => __('Link Text', Config::getTextDomain()),
          'name'  => self::FIELD_LINK_TEXT,
          'type'  => 'text',
          'instructions' => __('Optional text for the link.', Config::getTextDomain()),
        ],
        [
          'key'   => 'field_attrs',
          'label' => __('Attributes', Config::getTextDomain()),
          'name'  => self::FIELD_ATTRS,
          'type'  => 'text',
          'instructions' => __('Shortcode attributes as a string, e.g. utm_source=google utm_medium=cpc', Config::getTextDomain()),
        ],
      ],
      'location' => [
        [
          [
            'param'    => 'block',
            'operator' => '==',
            'value'    => 'acf/' . self::BLOCK_NAME,
          ],
        ],
      ],
    ]);
  }
}