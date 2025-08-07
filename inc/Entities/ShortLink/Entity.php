<?php 

namespace ShortLinks\Entities\ShortLink;

class Entity 
{
  public static function register() 
  {
    register_post_type(Constants::ENTITY_LABEL, [
      'label' => Constants::ENTITY_LABEL,
      'labels' => [
          'name'          => Constants::SINGULAR_NAME,
          'singular_name' => Constants::SINGULAR_NAME,
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