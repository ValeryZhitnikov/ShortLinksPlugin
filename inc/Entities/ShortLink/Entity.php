<?php 

namespace ShortLinks\Entities\ShortLink;

use ShortLinks\Interfaces\RegisterEntity;
class Entity implements RegisterEntity
{
  public static function register(): void {
    register_post_type(Constants::ENTITY_LABEL, [
      'label' => Constants::ENTITY_LABEL,
      'labels' => [
          'name'          => Constants::ENTITY_NAME,
          'singular_name' => Constants::SINGULAR_NAME,
      ],
      'supports'     => ['title'],
      'has_archive'  => true,
      'menu_icon'    => Constants::MENU_ICON,
      'public' => false,
      'show_ui' => current_user_can('edit_others_posts'),
      'capability_type' => 'post',
      'capabilities' => [
          'edit_post' => 'edit_others_posts',
          'read_post' => 'read',
          'delete_post' => 'delete_others_posts',
          'edit_posts' => 'edit_others_posts',
          'edit_others_posts' => 'edit_others_posts',
          'delete_posts' => 'delete_others_posts',
          'publish_posts' => 'publish_posts',
          'read_private_posts' => 'read_private_posts',
        ]
      ]);
  }
}