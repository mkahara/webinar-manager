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
		filemtime(plugin_dir_path(__FILE__) . 'dist/style.css')
	);
}
add_action('enqueue_block_assets', 'enqueue_webinar_promotion_block_assets');

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
	//$book = prefix_get_the_book( $request['id'] );

	// Check if a webinar is selected
	if (!empty($selectedWebinar)) {
		// Retrieve webinar details (title, subtitle, dates, speakers) using $selectedWebinar
		$webinar = get_post($selectedWebinar);

		// Check if webinar data is available
		if ($webinar) {
			$webinar_title = $webinar->post_title;
			// Assuming $webinar_subtitle, $webinar_start_date, $webinar_end_date are defined

			// Render HTML output
			ob_start();
			?>
			<div class="webinar-promotion-block">
				<div class="promo-card-content">
					<h2><?php echo esc_html($webinar_title); ?></h2>
					<h3>This is subtitle</h3>
					<ul>
						<li><strong>Begins at: </strong>12:00</li>
						<li><strong>Ends at: </strong>13:00</li>
						 <li><strong>Duration at: </strong>2 days</li>
					</ul>
					<a href="">Register Now</a>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	// Return an empty string if no webinar is selected or data is not available
	return '';
}
