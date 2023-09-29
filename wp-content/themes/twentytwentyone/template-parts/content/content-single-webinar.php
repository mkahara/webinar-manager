<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

/**
 * Get block content
 */
$webinar = get_post( $post->ID );
$content = $webinar->post_content;
$blocks  = parse_blocks( $content );

// Meta Icons
$calendar_icon = plugins_url( 'webinar-block/webinar-block/images/calendar.png' );
$clock_icon = plugins_url( 'webinar-block/webinar-block/images/clock.png' );
$hour_glass_icon = plugins_url( 'webinar-block/webinar-block/images/sand-clock.png' );

foreach ( $blocks as $block ) {
	if ( $block['blockName'] == 'webinar-block/webinar-block' ) {
		$attributes  = $block['attrs'];
		$subtitle    = $attributes['subtitle'] ?? 'Not available';
		$startDate   = $attributes['startDate'] ?? 'Not available';
		$endDate     = $attributes['endDate'] ?? 'Not available';
		$duration    = $attributes['duration'] ?? 'Not available';
		$description = $attributes['description'] ?? 'Not available';
		$webinarUrl = $attributes['webinarUrl'] ?? 'Not available';
		$regFormUrl = $attributes['regFormUrl'] ?? 'Not available';
	}
}

// Format time and display timezone
$formattedTime = date_i18n('H:i', strtotime($startDate));
$timezoneOffset = get_option('gmt_offset');
$formattedTimezone = ($timezoneOffset < 0 ? '-' : '+') . gmdate('H:i', abs($timezoneOffset) * 3600);
$formattedDateTime = $formattedTime . ', GMT' . $formattedTimezone;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header alignwide">
        <h1 class="entry-title">
            <div class="post-date-group">
                <span class="post-date">
                    <span class="post-day"><?php echo date( "d", strtotime( get_the_date( 'Y-m-d' ) ) ); ?></span>
                    <span class="post-month"><?php echo date( "M", strtotime( get_the_date( 'Y-m-d' ) ) ); ?></span>
                </span>
                <span class="post-title"><?php the_title(); ?></span>
            </div>
            <span><a href="<?php echo $regFormUrl ?>" target="_blank" class="clear-link">Register Now!</a></span>
        </h1>
        <p><?php echo $subtitle; ?></p>
        <div class="webinar-post-nav">
            <a href="#webinar-description">Details</a>
            <a href="#webinar-program">Program</a>
            <a href="#webinar-speakers">Speakers</a>
        </div>
        <div class="webinar-meta">
            <div class="meta-item">
                <span class="meta-icon"><img src="<?php echo $calendar_icon ?>"></span>
                <span class="meta-title">Date<br><strong><?php echo date_i18n('l, j F', strtotime($startDate)); ?></strong></span>
            </div>
            <div class="meta-item">
                <span class="meta-icon"><img src="<?php echo $clock_icon ?>"></span>
                <span class="meta-title">Time<br><strong><?php echo $formattedDateTime; ?></strong></span>
            </div>
            <div class="meta-item">
                <span class="meta-icon"><img src="<?php echo $hour_glass_icon ?>"></span>
                <span class="meta-title">Duration<br><strong><?php echo $duration; ?></strong></span>
            </div>
        </div>
		<?php twenty_twenty_one_post_thumbnail(); ?>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <div class="block-content">
            <!-- Webinar Description-->
            <div class="webinar-description" id="webinar-description">
                <h3 class="partial-underline">Webinar Details</h3>
				<?php echo $description ?>
                <a class="primary-button" href="<?php echo $webinarUrl ?>" target="_blank">Streaming Channel</a>
            </div>

            <!-- Webinar Highlights-->
            <div class="webinar-program" id="webinar-program">
				<?php
				$hasDisplayedH3 = false;
				foreach ( $blocks as $block ) {
					if ( $block['blockName'] === 'webinar-highlight/webinar-highlight' ) {
						$attributes = $block['attrs'];
						// Access the attributes of the block and render it as needed.
						$highlight_time  = $attributes['highlight_time'];
						$highlight_title = $attributes['highlight_title'];

						// Display h3 only if it hasn't been displayed yet
						if ( ! $hasDisplayedH3 ) {
							echo '<h3 class="partial-underline">Program</h3>';
							$hasDisplayedH3 = true; // Set the flag to true after displaying h3
						}

						// Render the block using the attributes.
						echo "<div class='webinar-highlights'>";
						echo "<span class='highlight-time'>$highlight_time</span>";
						echo "<span class='highlight-title'>$highlight_title</span>";
						echo "</div>";
					}
				}
				?>
            </div>

            <!-- Webinar Speakers-->
            <div class="webinar-speakers" id="webinar-speakers">
                <?php
                $selectedSpeakers = get_post_meta($post->ID, '_selected_speakers', true);
                $speaker_image = plugins_url( 'webinar-block/webinar-block/images/user.png' );

                if (!empty($selectedSpeakers)) {
	                echo '<h3 class="partial-underline">Speakers</h3>';
	                echo '<div class="speakers-list">';
	                foreach ($selectedSpeakers as $speaker) {
		                $speaker_info = get_userdata($speaker);
		                if ($speaker_info) {
			                echo '<div class="speaker">';
                            echo '<img src="'.$speaker_image.'">';
                            echo '<h4>' .$speaker_info->display_name. '</h4>';
			                echo '<p>' .$speaker_info->description. '</p>';
                            echo '</div>';
		                }
	                }
	                echo '</div>';
                }
                ?>
            </div>


        </div>


		<?php
		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
    </div><!-- .entry-content -->

    <footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
    </footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->

<script>
    document.querySelectorAll('.webinar-post-nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

</script>