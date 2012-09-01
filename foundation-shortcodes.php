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

	private static $footer_scripts = array();
	
	public function __construct() {
		require_once 'shortcodes/buttons.php';
		require_once 'shortcodes/elements.php';
		require_once 'shortcodes/grid.php';
		require_once 'shortcodes/tabs.php';
		require_once 'shortcodes/typography.php';
		
		add_filter('widget_text', 'do_shortcode');
		
		add_action('wp_print_footer_scripts', array($this, 'print_footer_scripts'));
		
		
		remove_filter('the_content', 'wpautop');
		remove_filter('the_content', 'wptexturize');
		
		add_filter('the_content', array($this, 'better_formatter'), 99);
	}
	
	public function print_footer_scripts() {
		if (empty(Foundation_Framework_Shortcodes::$footer_scripts)) return;
		
		echo '<script type="text/javascript">' . "\n";
		echo '/* <![CDATA[ */' . "\n";
		
		echo 'jQuery(document).ready(function ($) {' . "\n";
		
		foreach (Foundation_Framework_Shortcodes::$footer_scripts as $script) {
			echo $script . "\n";
		}
		
		echo '});' . "\n";
		echo '/* ]]> */' . "\n";
		echo '</script>' . "\n";
	}
	
	public static function sanitize_html_class_list($classes) {
		if (!is_array($classes)) {
			$classes = explode(' ', $classes);
		}
		
		$ret = '';
		foreach ($classes as $class) {
			$ret .= sanitize_html_class($class) .' ';
		}
		return trim($ret);
	}
	
	public static function add_footer_script($script) {
		if (!in_array($script, Foundation_Framework_Shortcodes::$footer_scripts)) {
			Foundation_Framework_Shortcodes::$footer_scripts[] = $script;
		}
	}
	
	function better_formatter($content)	{
		$new_content = '';
		$pattern_full = '{(\[raw\].*?\[/raw\])}is';
		$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
	
		foreach ($pieces as $piece)
		{
			if (preg_match($pattern_contents, $piece, $matches))
			{
				$new_content .= $matches[1];
			}
			else
			{
				$new_content .= wptexturize(wpautop($piece));
			}
		}
	
		return $new_content;
	}
}
new Foundation_Framework_Shortcodes();