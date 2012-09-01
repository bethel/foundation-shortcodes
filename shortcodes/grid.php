<?php 

class Foundation_Framework_Shortcodes_Grid {
	
	public function __construct() {
		add_shortcode('column', array($this, 'column'));
	}
		
	private $in_row = false;
	
	function column($atts, $content = null) {
		
		// make sure atts is an array
		if (!is_array($atts)) {
			$atts = array();
		}
				
		$widths = array('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve');
		$offsets = array();
		$pushes = array();
		foreach ($widths as $width) {
			$offsets[] = 'offset-by-'.$width;
			$pushes[] = 'push-'.$width;
			$pulls[] = 'pull-'.$width;
		}
		
		$column_class = array();
		foreach ($atts as $key=>$value) {
			
			// allow use of params without values
			if (is_numeric($key)) {
				$key = strtolower($value);
			}
			
			// skip redundant classes
			if (in_array($key, $column_class)) {
				continue;
			}
			
			// sanitize the values
			if (in_array($key, $widths)) {
				$column_class['width'] = $key;
			} else if (in_array($key, $offsets)) {
				$column_class['offset'] = $key;
			} else if (in_array($key, $pushes)) {
				$column_class['push'] = $key;
			} else if (in_array($key, $pulls)) {
				$column_class['pull'] = $key;
			} else if ($key == 'end' || $key == 'centered') {
				$column_class[$key] = $key;
			} else if ($key == 'class') {
				if (!empty($value)) {
					$column_class['extra_classes'] = $value;
				}
			}
		}
		extract($column_class);
		
		$html = '';
		if (!empty($width)) {
			
			if (!$this->in_row) {
				$this->in_row = true;
				$html .= '<div class="row">';
			}
			
			$class = "$width columns";
			
			if (!empty($centered)) {
				$class .= ' centered';
				// only one column allowed in a row when centered
				$end = 'end';
			} else if (!empty($offset)) {
				$class .= " $offset";
			} else if (!empty($push) || !empty($pull)) {
				if (!empty($push)) {
					$class .= " $push";
				} else {
					$class .= " $pull";
				};
			}
			
			if (!empty($end) && empty($centered)) {
				$class .= ' end';
			}
			
			if (!empty($extra_classes)) {
				$class .= ' ' . Foundation_Framework_Shortcodes::sanitize_html_class_list($extra_classes);
			}
			
			$html .= '<div class="'.trim($class).'">'.do_shortcode($content).'</div>';
		} else {
			$html .= 'WARNING: Must specify column width for:';
			$html .= '<pre>'.$content.'</pre>';
		}
		
		if (!empty($end) && $this->in_row) {
			$this->in_row = false;
			$html .= '</div>';
		}
		
		return $html;
	}
}
new Foundation_Framework_Shortcodes_Grid();
?>