<?php 

class Foundation_Framework_Shortcodes_Tabs {
	
	private $tabs;
	
	public function __construct() {
		add_shortcode('tabs', array($this, 'tabs'));
		add_shortcode('tab', array($this, 'tab'));
	}
	
	public function tabs($atts, $content) {
		
		// enqueue the js. works outside wp_enqueue_scripts in wp 3.3 and up
		wp_enqueue_script( 'jquery.foundation.tabs.js');
		Foundation_Framework_Shortcodes::add_footer_script('try {$(document).foundationTabs({callback:$.foundation.customForms.appendCustomMarkup});} catch (ex) {$(document).foundationTabs();}');
		
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
		
		// clear previous state
		$this->tabs = array();
		
		// array of the classes for the tab group
		$tab_container_classes = array('tabs');
		
		// array of the classes for the content ul
		$content_container_classes = array('tabs-content');
		
		// sanitize and process the classes of the tab group
		foreach ($atts as $key=>$value) {
			
			if (is_numeric($key)) {
				$key = $value;
			}
			
			$key = strtolower($key);
			
			if (in_array($key, array('two-up', 'three-up', 'four-up', 'five-up'))) {
				$tab_container_classes['size'] = $key;
			} else if ($key == 'contained' || $key == 'pill' || $key == 'vertical') {
				$tab_container_classes[$key] = $key;
				if ($key == 'contained') {
					$content_container_classes[$key] = $key;
				}
			} else if ($key == 'class') {
				$tab_container_classes = array_merge($tab_container_classes, explode(' ', $value));
				$content_container_classes = array_merge($content_container_classes, explode(' ', $value));
			}
		}
		
		// process the tabs
		do_shortcode($content);
		
		// make sure there is an active tab
		$has_hrefs = false;
		$has_active = false;
		foreach ($this->tabs as $tab) {
			if ($tab['active'] == true) {
				$has_active = true;
			}
			if (!empty($tab['href'])) {
				$has_hrefs = true;
			}
		}
		if (!$has_active && count($this->tabs) > 0 && !$has_hrefs) {
			$this->tabs[0]['active'] = true;
		}
		
		// build the html for the tabs
		$html .= '<dl class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($tab_container_classes).'">';
		$last_group = '';
		foreach ($this->tabs as $tab) {
			extract($tab);
			
			// find the group
			if (!empty($group) && strtolower($group) != strtolower($last_group)) {
				$html .= '<dt>'.$group.'</dt>';
			}
			$last_group = $group;
						
			$html .= '<dd';
			if ($active === true) {
				$class .= ' active';
			}
			if (!empty($class)) {
				$html .= ' class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($class).'"';
			}
			$html .= '>';
			
			if (empty($href)) {
				$href = $id;
			}
			
			$html .= '<a href="'.$href.'" title="'.$title.'">'.$title.'</a>';
			$html .= '</dd>';
		}
		$html .= '</dl>';
		
		// build the html for the tab content
		if (!$has_hrefs) {
			$html .= '<ul class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($content_container_classes).'">';
			foreach ($this->tabs as $tab) {
				extract($tab);
				
				if (!empty($href)) {
					continue;
				}
				
				$html .= '<li id="'.str_replace('#', '', $id).'Tab"';
				
				if ($active === true) {
					$class .= ' active';
				}
				
				if (!empty($class)) {
					$html .= ' class="'.Foundation_Framework_Shortcodes::sanitize_html_class_list($class).'"';
				}
				$html .= '>';
				
				$html .= do_shortcode($content);
				
				$html .= '</li>';
			}
			$html .= '</ul>';
		}
		
		return $html;
	}
	
	/**
	 * Processes individual tabs. Simply adds the tab to $this->tabs for tabs() to process further.
	 * @param unknown_type $atts
	 * @param unknown_type $content
	 */
	public function tab($atts, $content) {
		$default_title = 'tab'.count($this->tabs);
		
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
		
		extract( shortcode_atts( array(
			'id' => '',
			'title' => $default_title,
			'group' => '',
			'class' => '',
			'href' => ''
		), $atts ) );
		
		if (empty($id)) {
			$id = sanitize_title_with_dashes($title);
		}
		
		if (substr($id, 0, 1) != '#') {
			$id = '#'.$id;
		}
		
		$active = false;
		if (in_array('active', $atts)) {
			$active = true;
		}
		
		$this->tabs[] = array('id'=>$id, 'title'=>$title, 'group'=>$group, 'class'=>$class, 'content'=>$content, 'active'=>$active, 'href'=>$href);
	}
	
}
new Foundation_Framework_Shortcodes_Tabs();