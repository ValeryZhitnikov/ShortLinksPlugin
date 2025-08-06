<?php
/**
 * Plugin Name: Short links
 * Description: Создание коротких ссылок.
 * Version: 1.0
 * Author: Valery Zhitnikov
 */

if (!defined('ABSPATH')) {
    exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use ShortLinks\Shortlink;

Shortlink::get_instance();