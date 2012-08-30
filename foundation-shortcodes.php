<?php
/*
Plugin Name: Foundation Framework Shortcodes
Plugin URI: 
Description: Shortcodes to allow use of foundation framework grid, buttons, alerts, etc.
Version: 1.0
Author: Chris Roemmich
Author URI: https://cr-wd.com
License: MIT
*/

class Foundation_Framework_Shortcodes {

	public function __construct() {
		require_once 'shortcodes/buttons.php';
		require_once 'shortcodes/grid.php';
		require_once 'shortcodes/tabs.php';
		require_once 'shortcodes/typography.php';
		
		add_filter('widget_text', 'do_shortcode');
	}
	
	public static function sanitize_html_class_list($classes) {
		$ret = '';
		foreach (explode(' ', $classes) as $class) {
			$ret .= sanitize_html_class($class) .' ';
		}
		return trim($ret);
	}
	
}
new Foundation_Framework_Shortcodes();