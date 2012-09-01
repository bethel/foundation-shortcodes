<?php 

class Foundation_Framework_Shortcodes_Buttons {
	
	public function __construct() {
		add_shortcode('button', array($this, 'button'));
	}
	
	public function button($atts, $content = '') {
		
		// enqueue the js. works outside wp_enqueue_scripts in wp 3.3 and up
		wp_enqueue_script( 'jquery.foundation.buttons.js');
		Foundation_Framework_Shortcodes::add_footer_script('$(document).foundationButtons();');
		
		if (!is_array($atts)) {
			$atts = array();
		}
		
		$sizes = array('tiny', 'small', 'medium', 'large');
		$styles = array('radius', 'round');
		$colors = array('regular', 'success', 'alert', 'secondary');

		$classes = array();
		
		foreach ($atts as $key=>$value) {
			if (is_numeric($key)) {
				$key = $value;
			}
			
			// sanitize the values
			if (in_array($key, $sizes)) {
				$classes['size'] = $value;
			} else if (in_array($key, $styles)) {
				$classes['style'] = $value;
			} else if (in_array($key, $colors)) {
				$classes['color'] = $value;
			}
		}
		
		$classes['button'] = 'button';
		
		extract( shortcode_atts( array(
			'text' => $content,
			'href' => '#',
			'class' => '',
			'target' => '',
		), $atts ) );
		
		if (!empty($class)) {
			$classes = array_merge($classes, explode(' ', $class));
		}
		
		$classes = Foundation_Framework_Shortcodes::sanitize_html_class_list(implode(' ', $classes));
		
		$html = '<a href="'.$href.'" class="'.$classes.'"';
		if (!empty($title)) {
			$html .= ' title="'.$title.'"';
		}
		if (!empty($target)) {
			$html .= ' target="'.target.'"';
		}
		
		$html .= '>' . $text . '</a>';
		
		return $html;
	}
}
new Foundation_Framework_Shortcodes_Buttons();
?>