<?php
/**
 * Enqueue the block assets
 */
function enqueue_webinar_highlights_block_assets() {
	wp_enqueue_script(
		'webinar-highlights-block-script',
		plugins_url('dist/block.js', __FILE__),
		array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
		filemtime(plugin_dir_path(__FILE__) . 'dist/block.js')
	);

	wp_enqueue_style(
		'webinar-highlights-block-style',
		plugins_url('dist/style.css', __FILE__),
		array('wp-edit-blocks'),
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/style.css' )
	);
}
add_action('enqueue_block_assets', 'enqueue_webinar_highlights_block_assets');

/**
 * Enqueue the block editor assets
 */
function enqueue_webinar_highlights_block_assets_editor() {
	wp_enqueue_style(
		'webinar-highlights-block-style-editor',
		plugins_url('dist/editorStyle.css', __FILE__),
		array('wp-edit-blocks'),
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/editorStyle.css' )
	);
}
add_action('enqueue_block_editor_assets', 'enqueue_webinar_highlights_block_assets_editor');


/**
 * Register the Webinar Highlights Block
 */
function register_webinar_highlights_block() {
	register_block_type(
		'webinar-highlight/webinar-highlight',
		array(
			'render_callback' => 'render_webinar_highlights_block',
			'attributes' => array(
				'highlight_time' => array(
					'type' => 'string',
					'default' => '',
				),
				'highlight_title' => array(
					'type' => 'string',
					'default' => '',
				),
			),
		)
	);
}
add_action('init', 'register_webinar_highlights_block');

function render_webinar_highlights_block($attributes){
	$highlight_time = $attributes['highlight_time'];
	$highlight_title = $attributes['highlight_title'];

	ob_start();
	?>
	<div class="webinar-highlights">
		<span class="highlight-time"><?php echo esc_html($highlight_time); ?></span>
		<span class="highlight-title"><?php echo esc_html($highlight_title); ?></span>
	</div>
	<?php
	return ob_get_clean();
}
