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

use ShortLinks\Hooks\ActivationHook;
use ShortLinks\Hooks\InitHook;

register_activation_hook(__FILE__, [ActivationHook::class, 'activate']);
add_action('init', [InitHook::class, 'init']);