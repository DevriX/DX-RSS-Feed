<?php
/**
 * Plugin Name: DX RSS Feed
 * Plugin URI:  http://devrix.com/
 * Description: A shortcode for displaying RSS feeds
 * Version:     1.0
 * Author:      DevriX
 * Author URI:  http://devrix.com/
 * Text Domain: dx_rss_feed
 * Domain Path: /languages
 */

if ( ! defined( 'DX_RSS_FEED_DIR' ) ) {	
	define( 'DX_RSS_FEED_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'DX_RSS_FEED_URL' ) ) {
	define( 'DX_RSS_FEED_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! class_exists( 'Dx_Rss_Feed' ) ) :
class Dx_Rss_Feed {

	public function __construct() {
		//add dx rss feed shortcode
		add_action( 'init', array( $this, 'dx_rss_feed_shortcode') );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
	}

	function add_scripts() {
		wp_enqueue_style( 'dx-rss-feed-css',  DX_RSS_FEED_URL . '/assets/css/dx-rss-feed.css', array(), '1.0', 'screen' );
		
		wp_enqueue_script( 'dx-rss-feed-js', plugins_url( '/assets/js/dx-rss-feed.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
	}

	function dx_rss_feed_shortcode() {
		add_shortcode( 'dxrssfeed', array( $this, 'dx_rss_feed_callback' ) );
	}

	/**
	 * Function dx_rss_feed_callback
	 * 
	 * Example Shortcode:
	 * [dxrssfeed rss_url="http://devrix.com/feed/" before_text="Text Before" limit_feeds="10" show_rss_url="yes"]
	 * @param unknown $atts
	 */
	function dx_rss_feed_callback( $atts ) {
			
		extract( shortcode_atts(
			array(
				'rss_url'		=> '',
				'before_text'	=> '',
				'limit_feeds'	=> '10',
				'show_rss_url'	=> 'yes',
			), $atts
		) );
		
		$output = '';
	
		$rss = fetch_feed( $rss_url );
	
		if ( ! empty( $rss ) && ! is_wp_error( $rss ) ) {
			
			$rss_items = $rss->get_items( 0, $limit_feeds );
	
			if ( ! empty( $rss_items ) ) {
				$output = '<div class="dx-rss-feed-wrap">';
					
					if( $before_text ) {
						$output .= '<span class="dx-before-text">'.$before_text.'</span>';
					}
					
				 	$output .= '<ul class="feed-list">';
				 	foreach ( $rss_items as $item ) {
						$output .= '<li class="feed-item">';
					 		$output .= '<a href="'.$item->get_permalink().'" target="_blank">'.$item->get_title().'</a>';
					 	$output .= '</li>';
				 	}
					$output .= '</ul>';
	
				 	if( ! empty( $show_rss_url ) && $show_rss_url == 'yes' ) {
				 		$output .= '<a href="'.$rss_url.'" target="_blank" class="rss-feed-link">rss</a>';
				 	}
		 		$output .= '</div>';
		 	}	
		}
		
		return $output;
	}
}

$dx_rss_feed = new Dx_Rss_Feed();
endif;