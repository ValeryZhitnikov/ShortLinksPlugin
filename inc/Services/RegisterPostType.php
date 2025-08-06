<?php

namespace ShortLinks\Services;

class RegisterPostType
{
  public static function registerShortLinkType(): void
  {
    register_post_type('shortlink', [
      'label' => 'Short Links',
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