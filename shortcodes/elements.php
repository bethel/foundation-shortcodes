<?php 

class Foundation_Framework_Shortcodes_Elements {
	
	public function __construct() {
		add_shortcode('alert', array($this, 'alert'));
		add_shortcode('label', array($this, 'label'));
		add_shortcode('panel', array($this, 'panel'));
	}
	
	public function alert($atts, $content = '') {
		
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
		
		$colors = array('regular', 'success', 'alert', 'secondary');
		
		$classes = array();
		
		$no_close = false;
		
		foreach ($atts as $key=>$value) {
			if (is_numeric($key)) {
				$key = $value;
			}
			
			// sanitize the values
			if (in_array($key, $colors)) {
				$classes['color'] = $value;
			} else if ($key == 'no-close') {
				$no_close = true;
			}
		}
		
		$classes['alert-box'] = 'alert-box';
		
		extract( shortcode_atts( array(
			'text' => $content,
			'class' => ''
		), $atts ) );
		
		if (!empty($class)) {
			$classes = array_merge($classes, explode(' ', $class));
		}
			
		$classes = Foundation_Framework_Shortcodes::sanitize_html_class_list($classes);
		
		$html = '<div class="'.$classes.'">';
		$html .= $text;
		if (!$no_close) {
			$html .= '<a href="" class="close">&times;</a>';
		}
		$html .= '</div>';
		
		return $html;
	}
	
	public function label($atts, $content = '') {
	
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
	
		$styles = array('radius', 'round');
		$colors = array('regular', 'success', 'alert', 'secondary');
	
		$classes = array();
	
		foreach ($atts as $key=>$value) {
			if (is_numeric($key)) {
				$key = $value;
			}
				
			// sanitize the values
			if (in_array($key, $styles)) {
				$classes['style'] = $value;
			} else if (in_array($key, $colors)) {
				$classes['color'] = $value;
			}
		}
	
		$classes['label'] = 'label';
	
		extract( shortcode_atts( array(
			'text' => $content,
			'class' => ''
		), $atts ) );
	
		if (!empty($class)) {
			$classes = array_merge($classes, explode(' ', $class));
		}
			
		$classes = Foundation_Framework_Shortcodes::sanitize_html_class_list($classes);
		
		return '<span class="'.$classes.'">'.$text.'</span>';
	}
	
	public function panel($atts, $content = '') {
	
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
	
		$allowed = array('callout', 'radius');
	
		$classes = array('panel');
	
		foreach ($atts as $key=>$value) {
			if (is_numeric($key)) {
				$key = $value;
			}
				
			// sanitize the values
			if (in_array($key, $allowed)) {
				$classes[] = $value;
			}
		}
	
		extract( shortcode_atts( array(
			'text' => $content,
			'title' => '',
			'class' => ''
		), $atts ) );
	
		if (!empty($class)) {
			$classes = array_merge($classes, explode(' ', $class));
		}
			
		$classes = Foundation_Framework_Shortcodes::sanitize_html_class_list($classes);
		return '<div class="'.$classes.'">'.$text.'</div>';
	}
}
new Foundation_Framework_Shortcodes_Elements();
?>