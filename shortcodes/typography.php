<?php 

class Foundation_Framework_Shortcodes_Typography {
	
	public function __construct() {
		add_shortcode('print_only', array($this, 'print_only'));
		add_shortcode('hide_on_print', array($this, 'hide_on_print'));
		
		add_shortcode('h1', array($this, 'header'));
		add_shortcode('h2', array($this, 'header'));
		add_shortcode('h3', array($this, 'header'));
		add_shortcode('h4', array($this, 'header'));
		add_shortcode('h5', array($this, 'header'));
		add_shortcode('h6', array($this, 'header'));
		
		add_shortcode('blockquote', array($this, 'blockquote'));
	}
	
	public function print_only($atts, $content) {
		return '<div class="print-only" >'.do_shortcode($content).'</div>';
	}
	
	public function hide_on_print($atts, $content) {
		return '<div class="hide-on-print" >'.do_shortcode($content).'</div>';
	}
	
	public function header($atts, $content = '', $tag = 'h1') {
		
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
		
		$subheader = false;
		if (in_array('subheader', $atts)) {
			$subheader = true;
		}
		
		extract( shortcode_atts( array(
			'text' => $content,
			'class' => ''
		), $atts ) );
		
		$html = "<$tag";
		
		if ($subheader) {
			$class = 'subheader ' . $class;
		}
		if (!empty($class)) {
			$html .= ' class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($extra_classes).'"';
		}
		$html .= "/>";
		
		$html .= $text;
		
		$html .= "</$tag>";
		 
		return $html;
	}
	
	function blockquote($atts, $content = '') {
		extract( shortcode_atts( array(
			'text' => $content,
			'cite' => '',
			'class' => '',
		), $atts ) );
		
		$html = '<blockquote';
		if (!empty($class)) {
			$html .= ' class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($class).'"';
		}
		$html.='>' . $text;
		if (!empty($cite)) {
			$html .= "<cite>$cite</cite>";
		}
		$html .= '<blockquote>';
		
		return $html;
	}
}
new Foundation_Framework_Shortcodes_Typography();
?>