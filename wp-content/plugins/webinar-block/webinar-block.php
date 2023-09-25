<?php
/**
 * Plugin Name:       Webinar Block
 * Description:       A WordPress plugin to enable managing webinars on a website.
 * Version:           1.0.0
 * Author:            Samuel Kahara
 * Author URI:        https://github.com/mkahara/webinar-manager
 * License:           MIT
 * Text Domain:       webinar-block
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_webinar_block_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_webinar_block_block_init' );

// Register the Webinar custom post type
function register_webinar_post_type() {
	$labels = array(
		'name' => __( 'Webinars', 'webinar-block' ),
		'singular_name' => __( 'Webinar', 'webinar-block' ),
		'add_new' => __( 'New Webinar', 'webinar-block' ),
		'add_new_item' => __( 'Add New Webinar', 'webinar-block' ),
		'edit_item' => __( 'Edit Webinar', 'webinar-block' ),
		'new_item' => __( 'New Webinar', 'webinar-block' ),
		'view_item' => __( 'View Webinars', 'webinar-block' ),
		'search_items' => __( 'Search Webinars', 'webinar-block' ),
		'not_found' =>  __( 'No Webinars Found', 'webinar-block' ),
		'not_found_in_trash' => __( 'No Webinars found in Trash', 'webinar-block' ),
	);

	$args = array(
		'labels' => $labels,
		'has_archive' => true,
		'public' => true,
		'hierarchical' => false,
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'custom-fields',
			'thumbnail',
			'page-attributes'
		),
		'taxonomies' => 'category',
		'rewrite'   => array( 'slug' => 'webinar' ),
		'show_in_rest' => true,
		'menu_icon' => 'dashicons-welcome-view-site',
	);

	register_post_type( 'webinar', $args );
}
add_action( 'init', 'register_webinar_post_type' );

// Define the 'Category' taxonomy
function register_webinar_category_taxonomy() {
	$args = array(
		'hierarchical' => true,
		'label' => 'Categories',
		'rewrite' => array( 'slug' => 'webinar-category' ),
	);
	register_taxonomy( 'webinar_category', 'webinar', $args );
}
add_action( 'init', 'register_webinar_category_taxonomy' );
