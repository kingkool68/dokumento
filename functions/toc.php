<?php
class Dokumento_TOC {
	
	//Properties
	protected static $instance = null;
	
	public $headings = NULL;
	
	//Methods
	private function __construct() {
		add_filter( 'the_content', array( $this, 'the_content' ), 100 ); // run after shortcodes are interpretted (level 10)
	}
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	public function the_content( $the_content ) {
		if( !is_single() ) {
			return $the_content;
		}
		
		$find = $replace = array();
		$items = $this->get_headings( $find, $replace, $the_content );
		$the_content = $this->mb_find_replace($find, $replace, $the_content);
		
		return $the_content;
	}
	
	public function get_headings( &$find, &$replace, $text ) {
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
				$find[] = $matches[$i][0];
				$replace[] = str_replace(
					array(
						$matches[$i][1],				// start of heading
						'</h' . $matches[$i][2] . '>'	// end of heading
					),
					array(
						$matches[$i][1] . '<a id="' . $anchor . '" href="#' . $anchor .'">#</a>',
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
			
			// remove &amp;
			//$return = str_replace( '&amp;', '', $return );
			
			// remove non alphanumeric chars
			//$return = preg_replace( '/[^a-zA-Z0-9 \-_]*/', '', $return );
			
			// convert spaces to _
			/*
			$return = str_replace(
				array('  ', ' '),
				'_',
				$return
			);
			
			*/
			// remove trailing - and _
			//$return = rtrim( $return, '-_' );
			
			// lowercase everything?
			//if ( $this->options['lowercase'] ) $return = strtolower($return);

			// if blank, then prepend with the fragment prefix
			// blank anchors normally appear on sites that don't use the latin charset
			/*
			if ( !$return ) {
				$return = ( $this->options['fragment_prefix'] ) ? $this->options['fragment_prefix'] : '_';
			}
			*/
			
			// hyphenate?
			/*
			if ( $this->options['hyphenate'] ) {
				$return = str_replace('_', '-', $return);
				$return = str_replace('--', '-', $return);
			}
			*/
		}
		
		/*
		if ( array_key_exists($return, $this->collision_collector) ) {
			$this->collision_collector[$return]++;
			$return .= '-' . $this->collision_collector[$return];
		}
		else
			$this->collision_collector[$return] = 1;
		*/
		
		return $return;
	}
	
	private function mb_find_replace( &$find = false, &$replace = false, &$string = '' ) {
		if ( is_array($find) && is_array($replace) && $string ) {
			// check if multibyte strings are supported
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
		}
		
		return $string;
	}
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
	
	$count = 1;
	
	$output = '<div class="dokumento-toc">';
	$output = '<h2>Contents</h2>';
	$output .= '<ul>';
	foreach( $headers as $header ) {
		
		if( isset( $headers[$count] ) ) {
			$next_header = $headers[$count];
		} else {
			$next_header = (object) array(
				'level' => 0
			);
		}
		
		$start = '<li class="level-' . $header->level . '"><a href="#' . $header->anchor . '">' . $header->text . '</a>';
		$end = '</li>';
		
		if( $next_header->level > $header->level ) {
			$start .= '<ul>';
		} else if( $next_header->level < $header->level ) {
			$end = '</ul>' . $end;
		}
		
		$current_heading_level = $header->level;
		$output .= $start . $end;
		
		$count++;
	}
	$output .= '</ul>';
	$output .= '</div>';
	
	echo $output;	
}