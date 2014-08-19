<?php
class Dokumento_TOC {
	
	//Properties
	protected static $instance = null;
	
	public $headings = NULL;
	
	public $find = array();
	
	public $replace = array();
	
	//Methods
	private function __construct() {
		add_action( 'the_post', array( $this, 'the_post' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), 100 ); // run after shortcodes are interpretted (level 10)
	}
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	public function the_post( $post ) {
		if( !is_single() ) {
			return $post;
		}
		
		$find = $replace = array();
		$this->get_headings( $post->post_content );
		
		return $post;
	}
	
	public function the_content( $the_content ) {
		if( !is_single() ) {
			return $the_content;
		}
		
		$the_content = $this->mb_find_replace($the_content);
		
		return $the_content;
	}
	
	public function get_headings( $text ) {
		$matches = array();
		if ( preg_match_all('/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $text, $matches, PREG_SET_ORDER) ) {
			/*
			$matches[x][0] - Full HTML heading, <h1>Hello World!</h1>
			$matches[x][1] - Opening headling tag, <h1> or <h2> etc.
			$matches[x][2] - Numeric heading level, 1 or 2
			*/
			
			$output = array();
			for ($i = 0; $i < count($matches); $i++) {
				// get anchor and add to find and replace arrays
				$anchor = $this->url_anchor_target( $matches[$i][0] );
				$this->find[] = $matches[$i][0];
				$this->replace[] = str_replace(
					array(
						$matches[$i][1],				// start of heading
						'</h' . $matches[$i][2] . '>'	// end of heading
					),
					array(
						$matches[$i][1] . '<a id="' . $anchor . '" href="#' . $anchor .'" class="toc-anchor">#</a>',
						'</h' . $matches[$i][2] . '>'
					),
					$matches[$i][0]
				);
	
				$output[] = (object) array(
					'anchor' => $anchor,
					'text' => strip_tags( $matches[$i][0] ),
					'html' => $matches[$i][0],
					'opening_tag' => $matches[$i][1],
					'level' => intval($matches[$i][2]),
				);
			}
			
			$this->headings = $output;
			
			return $output;
		}
	}
	
	private function url_anchor_target( $title ) {
		$return = false;
			
		if ( $title ) {
			$return = trim( strip_tags($title) );
			
			// replace newlines with spaces (eg when headings are split over multiple lines)
			$return = str_replace( array("\r", "\n", "\n\r", "\r\n"), ' ', $return );
			
			$return = sanitize_file_name($return);
			
		}
		
		return $return;
	}
	
	private function mb_find_replace( &$string = '' ) {
		// check if multibyte strings are supported
		
		$find = $this->find;
		$replace = $this->replace;
		
		if ( function_exists( 'mb_strpos' ) ) {
			for ($i = 0; $i < count($find); $i++) {
				$string = 
					mb_substr( $string, 0, mb_strpos($string, $find[$i]) ) .	// everything befor $find
					$replace[$i] .												// its replacement
					mb_substr( $string, mb_strpos($string, $find[$i]) + mb_strlen($find[$i]) )	// everything after $find
				;
			}
		}
		else {
			for ($i = 0; $i < count($find); $i++) {
				$string = substr_replace(
					$string,
					$replace[$i],
					strpos($string, $find[$i]),
					strlen($find[$i])
				);
			}
		}
		
		return $string;
	}

//End Dokumento_TOC Class
}

add_action( 'after_setup_theme', array( 'Dokumento_TOC', 'get_instance' ) );

/* Helper Functions */
function get_dokumento_toc() {
	$dokumento = Dokumento_TOC::get_instance();
	return $dokumento->headings;
}

function dokumento_toc() {
	$headers = get_dokumento_toc();
	if( !$headers ) {
		return;
	}
	
	
	$output = '<div class="dokumento-toc">';
	$output .= '<h2>Contents</h2>';
	$output .= '<ol>';
	
	$current_depth = 100;	// headings can't be larger than h6 but 100 as a default to be sure
	$numbered_items = array();
	$numbered_items_min = null;
	
	// find the minimum heading to establish our baseline
	foreach($headers as $header) {
		if ( $current_depth > $header->level )
			$current_depth = $header->level;
	}
	
	$numbered_items[$current_depth] = 0;
	$numbered_items_min = $current_depth;
	
	foreach($headers as $i => $header) {
		$the_next_header = $headers[$i + 1];

		if ( $current_depth == $header->level ) {
			$output .= '<li class="level-' . $header->level . '">';
		}
	
		// start lists
		if ( $current_depth != $header->level ) {
			for ($current_depth; $current_depth < $header->level; $current_depth++) {
				$numbered_items[$current_depth + 1] = 0;
				$output .= '<ol><li class="level-' . $header->level . '">';
			}
		}
		
		// list item
		$output .= '<a href="#' . $header->anchor . '">' . $header->text . '</a>';
		
		
		// end lists
		if ( $i != count($headers) - 1 ) {
			if ( $current_depth > $the_next_header->level ) {
				for ($current_depth; $current_depth > $the_next_header->level; $current_depth--) {
					$output .= '</li></ol>';
					$numbered_items[$current_depth] = 0;
				}
			}
			
			if ( $current_depth == $the_next_header->level ) {
				$output .= '</li>';
			}
		} else {
			// this is the last item, make sure we close off all tags
			for ($current_depth; $current_depth >= $numbered_items_min; $current_depth--) {
				$output .= '</li>';
				if ( $current_depth != $numbered_items_min ) {
					$output .= '</ol>';
				}
			}
		}
	}
	
	$output .= '</ol>';
	$output .= '</div>';
	
	return $output;
}

//Add the TOC just before the first headline just like Wikipedia does.
function dokumento_toc_the_content( $content ) {
	if( is_single() ) {
		$toc = dokumento_toc();
		if( !$toc ) {
			return $content;
		}
		
		$content = preg_replace('/<h(\d)/im', $toc . "\n\n<h$1", $content, 1);
	}
	return $content;
}
add_filter( 'the_content', 'dokumento_toc_the_content' );