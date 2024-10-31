<?php
/*
Plugin Name: Remove Feed Links
Plugin URI: http://wpsos.io/wordpress-plugin-remove-feed-links
Description: A simple plugin for removing feed link from the head of your web site.
Author: WPSOS
Version: 1.0
Author URI: http://wpsos.io/
*/

//Require the necessary plugin files
require_once( __DIR__ . '/settings-page.php' );

/**
 * Remove feed links from the head tag
 */
function wpsos_rfl_remove_feed_links(){
	//Get the options
	$options = unserialize( get_option( '_wpsos_rfl_options' ) );
	//If remove category feed
	if( $options['remove_category_feed'] ){
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
	//If remove posts or comments feed
	if( $options['remove_posts_feed'] || $options['remove_comments_feed'] ){
		remove_action( 'wp_head', 'feed_links', 2 );
		//If don't remove posts feed, add it back
		if( !$options['remove_posts_feed'] ){
			add_action('wp_head', 'wpsos_rfl_add_posts_feed');
		}
		if( !$options['remove_comments_feed'] ){
			add_action('wp_head', 'wpsos_rfl_add_comments_feed');
		}
	}
}
add_action( 'after_setup_theme', 'wpsos_rfl_remove_feed_links' );

/**
 * Add posts feed
 */
function wpsos_rfl_add_posts_feed() {
	$args = array(
			'separator'	=> _x('&raquo;', 'feed link'),
			'feedtitle'	=> __('%1$s %2$s Feed'),
			'comstitle'	=> __('%1$s %2$s Comments Feed'),
	);
	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( sprintf( $args['feedtitle'], get_bloginfo( 'name' ), $args['separator'] ) ) . '" href="' . esc_url( get_feed_link() ) . "\" />\n";
}

/**
 * Add comments feed
 */
function wpsos_rfl_add_comments_feed() {
	$args = array(
			'separator'	=> _x('&raquo;', 'feed link'),
			'feedtitle'	=> __('%1$s %2$s Feed'),
			'comstitle'	=> __('%1$s %2$s Comments Feed'),
	);
	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( sprintf( $args['comstitle'], get_bloginfo( 'name' ), $args['separator'] ) ) . '" href="' . esc_url( get_feed_link( 'comments_' . get_default_feed() ) ) . "\" />\n";
}

/**
 * Additional links to the plugin page
 * 
 * @param Array $links
 * @param String $file
 * @return multitype:
 */
function wpsos_rfl_ace_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'remove-feed-links.php' ) !== false ) {	
		$links = array_merge( $links, array( '<a href="' . get_admin_url() . 'options-general.php?page=remove-feed-links">' . __( 'Settings' ) . '</a>' ) );
		$links = array_merge( $links, array( '<a href="http://www.wpsos.io/">WordPress Security & Hack Repair</a>' ) );		
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'wpsos_rfl_ace_set_plugin_meta', 10, 2 );

if( is_admin() ){
	//Register plugin scripts
	add_action( 'admin_enqueue_scripts', 'wpsos_rfl_register_scripts' );
}

/**
 * Register plugin scripts
 */
function wpsos_rfl_register_scripts(){
	wp_enqueue_style( 'wpsos-rfl-style', plugin_dir_url( __FILE__ ) . 'css/style.css' );
}
?>