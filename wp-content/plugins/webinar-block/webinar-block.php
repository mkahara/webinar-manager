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

// Call the webinar promotion block
require_once( plugin_dir_path( __FILE__ ) . '/webinar-promotion-block/webinar-promotion.php' );

// Call the webinar highlights block
require_once( plugin_dir_path( __FILE__ ) . '/webinar-highlights-block/webinar-highlights-block.php' );

function create_webinar_block_init() {
	register_block_type( __DIR__ . '/webinar-block/build'
	);
}

add_action( 'init', 'create_webinar_block_init' );

// Register the Webinar custom post type
function register_webinar_post_type() {
	$labels = array(
		'name'               => __( 'Webinars', 'webinar-block' ),
		'singular_name'      => __( 'Webinar', 'webinar-block' ),
		'add_new'            => __( 'New Webinar', 'webinar-block' ),
		'add_new_item'       => __( 'Add New Webinar', 'webinar-block' ),
		'edit_item'          => __( 'Edit Webinar', 'webinar-block' ),
		'new_item'           => __( 'New Webinar', 'webinar-block' ),
		'view_item'          => __( 'View Webinars', 'webinar-block' ),
		'search_items'       => __( 'Search Webinars', 'webinar-block' ),
		'not_found'          => __( 'No Webinars Found', 'webinar-block' ),
		'not_found_in_trash' => __( 'No Webinars found in Trash', 'webinar-block' ),
	);

	$args = array(
		'labels'       => $labels,
		'has_archive'  => true,
		'public'       => true,
		'hierarchical' => false,
		'supports'     => array(
			'title',
			'editor',
			'excerpt',
			'custom-fields',
			'thumbnail',
			'page-attributes'
		),
		'taxonomies'   => array( 'webinar_category' ),
		'rewrite'      => array( 'slug' => 'webinar' ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-welcome-view-site',
	);

	register_post_type( 'webinar', $args );
}

add_action( 'init', 'register_webinar_post_type' );

// Define the 'Webinar Category' taxonomy
function register_webinar_category_taxonomy() {
	$args = array(
		'hierarchical' => true,
		'label'        => 'Categories',
		'rewrite'      => array( 'slug' => 'webinar-category' ),
		'show_in_rest' => true
	);
	register_taxonomy( 'webinar_category', 'webinar', $args );
}

add_action( 'init', 'register_webinar_category_taxonomy' );

// Load the custom template
function load_custom_webinar_template( $template ) {
	if ( is_singular( 'webinar' ) ) {
		$template = plugin_dir_path( __FILE__ ) . 'templates/single-webinar.php';
	}

	return $template;
}

//add_filter('template_include', 'load_custom_webinar_template');

function register_webinar_highlights_pattern() {
	register_block_pattern(
		'webinar-highlights/highlights-pattern',
		array(
			'title'       => __( 'Webinar Highlights', 'webinar-highlights' ),
			'description' => _x( 'Two horizontal fields, the left field displays the time, and the right field displays the title.', 'Block pattern description', 'webinar-highlights' ),
			'content'     => '<div class="highlight-pattern"><span class="highlight-time">1630</span><span class="highlight-title">Inteligencia Financeira e Economica nos Mercados de Capitais</span></div>',
		)
	);

}

add_action( 'init', 'register_webinar_highlights_pattern' );

/**
 * Create a custom role named Speaker
 * The speaker role will assume the capabilities of the subscriber role
 */
function create_speaker_role() {
	if ( get_option( 'custom_roles_version' ) < 1 ) {
		add_role( 'speaker', 'Speaker', get_role( 'subscriber' )->capabilities );
		update_option( 'custom_roles_version', 1 );
	}
}

add_action( 'init', 'create_speaker_role' );

/**
 * Register a metabox in the webinar post type
 * The metabox will contain the panel to select speakers
 */
function add_speakers_meta_box() {
	add_meta_box(
		'speakers-meta-box',
		'Speakers',
		'render_speakers_meta_box',
		'webinar',
		'side',
		'default'
	);
}

add_action( 'add_meta_boxes', 'add_speakers_meta_box' );

/**
 * Render the speakers list in form of checkboxes
 */
function render_speakers_meta_box( $post ) {
	// Get all users with 'speaker' role
	$speakers = get_users( array( 'role' => 'speaker' ) );

	// Get selected speakers for the current webinar
	$selected_speakers = get_post_meta( $post->ID, '_selected_speakers', true );

	// Check if there are selected speakers
	if ( $selected_speakers ) {
		foreach ( $speakers as $speaker ) {
			$checked = in_array( $speaker->ID, $selected_speakers ) ? 'checked' : '';
			echo "<label><input type='checkbox' name='selected_speakers[]' value='{$speaker->ID}' $checked>{$speaker->display_name}</label><br>";
		}
	} else {
		foreach ( $speakers as $speaker ) {
			echo "<label><input type='checkbox' name='selected_speakers[]' value='{$speaker->ID}'>{$speaker->display_name}</label><br>";
		}
	}
}

/**
 * Save the selected speakers
 * They will be saved in the post_meta with _selected_speakers key
 */
function save_speakers_meta( $post_id ) {
	// Sanitize and save the selected speakers.
	if ( isset( $_POST['selected_speakers'] ) && is_array( $_POST['selected_speakers'] ) ) {
		$selected_speakers = array_map( 'intval', $_POST['selected_speakers'] );
		update_post_meta( $post_id, '_selected_speakers', $selected_speakers );
	} else {
		// If no speakers are selected, remove the meta key.
		delete_post_meta( $post_id, '_selected_speakers' );
	}
}
add_action( 'save_post', 'save_speakers_meta' );

// Add the 'webinar_category' column to the admin list view
function add_webinar_category_column($columns) {
	$columns['webinar_category'] = 'Webinar Category';
	return $columns;
}
add_filter('manage_webinar_posts_columns', 'add_webinar_category_column');

// Populate the 'webinar_category' column with the custom taxonomy terms
function populate_webinar_category_column($column, $post_id) {
	if ($column == 'webinar_category') {
		$terms = get_the_terms($post_id, 'webinar_category');
		if ($terms && !is_wp_error($terms)) {
			$term_names = array();
			foreach ($terms as $term) {
				$term_names[] = $term->name;
			}
			echo implode(', ', $term_names);
		} else {
			echo 'No Category';
		}
	}
}
add_action('manage_webinar_posts_custom_column', 'populate_webinar_category_column', 10, 2);
