<?php
/**
 * Enqueue the block assets
 */
function enqueue_webinar_promotion_block_assets() {
	wp_enqueue_script(
		'webinar-promotion-block-script',
		plugins_url('dist/block.js', __FILE__),
		array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
		filemtime(plugin_dir_path(__FILE__) . 'dist/block.js')
	);

	wp_enqueue_style(
		'webinar-promotion-block-style',
		plugins_url('dist/style.css', __FILE__),
		array('wp-edit-blocks'),
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/style.css' )
	);
}
add_action('enqueue_block_assets', 'enqueue_webinar_promotion_block_assets');

/**
 * Enqueue the block editor assets
 */
function enqueue_webinar_promotion_block_assets_editor() {
	wp_enqueue_style(
		'webinar-promotion-block-style-editor',
		plugins_url('dist/editorStyle.css', __FILE__),
		array('wp-edit-blocks'),
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/editorStyle.css' )
	);
}
add_action('enqueue_block_editor_assets', 'enqueue_webinar_promotion_block_assets_editor');

/**
 * Register the Webinar Promotion Block
 */
function register_webinar_promotion_block() {
	register_block_type(
		'webinar-promotion/webinar-promotion-block',
		array(
			'render_callback' => 'render_webinar_promotion_block',
			'attributes' => array(
				'selectedWebinar' => array(
					'type' => 'string',
					'default' => '',
				),
			),
		),
	);
}
add_action('init', 'register_webinar_promotion_block');

add_action('rest_api_init', function () {
	register_rest_route('webinar-manager/v1', '/get_webinar_details/', array(
		'methods' => 'POST',
		'callback' => 'render_webinar_promotion_block',
		'permission_callback' => '__return_true',
	));
});

function render_webinar_promotion_block($request) {
	$selectedWebinar = $request['selectedWebinar'];

	$current_plugin_dir = dirname(plugin_basename(__FILE__));
	$current_plugin_url = plugins_url('', $current_plugin_dir);
	// Check if a webinar is selected
	if (isset($selectedWebinar)) {
		$webinar = get_post($selectedWebinar);

		// Check if webinar data is available
		if ($webinar) {
			$webinar_title = $webinar->post_title;

            $content = $webinar->post_content;
			$blocks = parse_blocks($content);

            foreach ($blocks as $block) {
                if ($block['blockName'] == 'webinar-block/webinar-block') {
	                $attributes = $block['attrs'];
	                $subtitle = $attributes['subtitle'] ?? 'Not available';
	                $startDate = $attributes['startDate'] ?? 'Not available';
	                $endDate = $attributes['endDate'] ?? 'Not available';
	                $duration = $attributes['duration'] ?? 'Not available';
                }
            }

			// Render HTML output
			ob_start();
			?>
			<div class="webinar-promotion-block">
				<div class="promo-card-content">
					<h2><?php echo esc_html($webinar_title); ?></h2>
					<h3><?php echo esc_html($subtitle); ?></h3>
					<ul>
						<li><strong>Begins at: </strong><?php echo date("F j, Y g:i A", strtotime($startDate)); ?></li>
						<li><strong>Ends at: </strong><?php echo date("F j, Y g:i A", strtotime($endDate)); ?></li>
						 <li><strong>Duration: </strong><?php echo esc_html($duration); ?></li>
					</ul>
					<a href=" <?php echo site_url().'/?p='.$selectedWebinar; ?>">Register Now</a>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	// Return an empty string if no webinar is selected or data is not available
	return '';
}
