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

	public static $path;
	public static $url;
	
	private static $footer_scripts = array();
	
	public function __construct() {
		$this->init_paths();
		
		require_once 'shortcodes/accordion.php';
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
		
		add_action('wp_enqueue_scripts', array($this, 'foundation_js'), 9); // called before default to allow themes to modify js
	}
	
	/** attempts to determine the correct paths when symlinked */
	function init_paths() {
		if (defined("ABSPATH")) {
			// check if the file systems match, if not, the plugin is likely symlinked
			if (strpos(plugin_dir_path( __FILE__ ), ABSPATH) !== 0) {
				// assume the plugin is in the default spot
				Foundation_Framework_Shortcodes::$path = ABSPATH . 'wp-content/plugins/foundation-shortcodes/';
				Foundation_Framework_Shortcodes::$url = site_url('/') . 'wp-content/plugins/foundation-shortcodes/';
				return;
			}
		}
		// go with the "safe" values
		Foundation_Framework_Shortcodes::$path = plugin_dir_path( __FILE__ );
		Foundation_Framework_Shortcodes::$url = plugin_dir_url( __FILE__ );
	}
	
	public function print_footer_scripts() {
		if (empty(Foundation_Framework_Shortcodes::$footer_scripts)) return;
		
		echo '<script type="text/javascript">' . "\n";
		echo '/* <![CDATA[ */' . "\n";
		
		echo '(function($){'."\n";
		echo '	$(function(){' ."\n";
		
		foreach (Foundation_Framework_Shortcodes::$footer_scripts as $script) {
			echo $script . "\n";
		}
		
		echo '	})';
		echo '})(jQuery);';
		
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
	
	function foundation_js() {
		wp_register_script( 'jquery.foundation.buttons.js', Foundation_Framework_Shortcodes::$url . 'js/jquery.foundation.buttons.js', array('jquery'), '2.6.0', true );
		wp_register_script( 'jquery.foundation.tabs.js', Foundation_Framework_Shortcodes::$url . 'js/jquery.foundation.tabs.js', array('jquery'), '2.6.0', true );
		wp_register_script( 'jquery.foundation.accordion.js', Foundation_Framework_Shortcodes::$url . 'js/jquery.foundation.accordion.js', array('jquery'), '2.6.0', true );
		wp_register_script( 'jquery.foundation.alerts.js', Foundation_Framework_Shortcodes::$url . 'js/jquery.foundation.alerts.js', array('jquery'), '2.6.0', true );
	}
}
new Foundation_Framework_Shortcodes();