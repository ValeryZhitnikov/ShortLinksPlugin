<?php

namespace ShortLinks\Services;

use ShortLinks\Config\Constants;

class RegisterPostType
{
  public static function registerShortLinkType(): void
  { 
    register_post_type(Constants::POST_TYPE_SHORTLINK_LABEL, [
      'label' => Constants::POST_TYPE_SHORTLINK_LABEL,
      'labels' => [
          'name'          => 'Short Links',
          'singular_name' => 'Short Link',
      ],
      'public'       => true,
      'supports'     => ['title'],
      'has_archive'  => true,
      'menu_icon'    => 'dashicons-admin-links',
      'menu_position'=> 5,
      'capability_type' => 'post',
    ]);
  }
}