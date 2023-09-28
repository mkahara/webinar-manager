<?php
/**
 * Template Name: Single Webinar
 */

if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-content">
			<p>Subtitle: <?php echo get_post_meta(get_the_ID(), 'subtitle', true); ?></p>
			<p>Start Date: <?php echo get_post_meta(get_the_ID(), 'startDate', true); ?></p>
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile; endif; ?>
