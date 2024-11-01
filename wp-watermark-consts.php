<?php

$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root . '/wp-load.php')) {
	require_once($root . '/wp-load.php'); // WP 2.6
} else {
	require_once($root . '/wp-config.php'); // Before 2.6
}

if (!defined('WP_CONTENT_DIR')) {
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}

// GamerZ and Ozh deserve to go to WP developers' paradise for the code above

if (!defined('WP_WATERMARK_DIR_NAME')) {
	define('WP_WATERMARK_DIR_NAME', plugin_basename(dirname(__FILE__)));
}
if (!defined('WP_WATERMARK_DIR')) {
	define('WP_WATERMARK_DIR', WP_PLUGIN_DIR . '/' . WP_WATERMARK_DIR_NAME);
}
if (!defined('WP_WATERMARK_URL')) {
	define('WP_WATERMARK_URL', WP_PLUGIN_URL . '/' . WP_WATERMARK_DIR_NAME);
}

if (!defined('WP_WATERMARK_KEY')) {
	switch (true) {
		case defined('AUTH_KEY'): // WP 2.6
			define('WP_WATERMARK_KEY', AUTH_KEY);
			break;
		case defined('SECRET_KEY'): // WP 2.5
			define('WP_WATERMARK_KEY', SECRET_KEY);
			break;
		default: // Prior to WP 2.5
			define('WP_WATERMARK_KEY', WP_WATERMARK_DIR_NAME);
			break;
	}
}

?>
