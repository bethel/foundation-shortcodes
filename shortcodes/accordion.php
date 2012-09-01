<?php 

class Foundation_Framework_Shortcodes_Accordion {
	
	private $items;
	
	public function __construct() {
		add_shortcode('accordion', array($this, 'accordion'));
		add_shortcode('accordion_item', array($this, 'accordion_item'));
	}
	
	public function accordion($atts, $content) {
		
		// enqueue the js. works outside wp_enqueue_scripts in wp 3.3 and up
		wp_enqueue_script( 'jquery.foundation.accordion.js');
		Foundation_Framework_Shortcodes::add_footer_script('$(document).foundationAccordion();');
		
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
		
		// clear previous state
		$this->items = array();
		
		// array of the classes for the accordion
		$container_class = array('accordion');
		
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );
		$container_class = array_merge($container_class, explode(' ', $class));
		
		// process the items
		do_shortcode($content);
		
		// build the html for the tabs
		$html .= '<ul class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($container_class).'">';
		foreach ($this->items as $item) {
			extract($item);
						
			$html .= '<li';
			if (!empty($class)) {
				$html .= ' class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($class).'"';
			}
			$html .= '>';
		
			$html .= '<div class="title"><h5>'.$title.'</h5></div>';
			$html .= '<div class="content">'.do_shortcode($content).'</div>';
			
			$html .= '</li>';
		}
		$html .= '</ul>';
		
		return $html;
	}
	
	/**
	 * Processes individual tabs. Simply adds the tab to $this->tabs for tabs() to process further.
	 * @param unknown_type $atts
	 * @param unknown_type $content
	 */
	public function accordion_item($atts, $content) {
		extract( shortcode_atts( array(
			'title' => '',
			'class' => ''
		), $atts ) );
		
		$this->items[] = array('title'=>$title, 'class'=>$class, 'content'=>$content);
	}
	
}
new Foundation_Framework_Shortcodes_Accordion();