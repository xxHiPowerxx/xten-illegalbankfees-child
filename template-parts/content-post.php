<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

function use_acf_field_h1() {
	$h1 = wp_kses_post( get_field( 'page_heading_override', false, false ) );
	return $h1;
}
$get_the_title = use_acf_field_h1() === '' ? get_the_title() : use_acf_field_h1();

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-area card-style' ); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ) :
			$hide_post_image = get_post_meta( $post->ID, 'hide_featured_image', true );
			if ( ! $hide_post_image ) :
				?>
				<div class="featured-image">
					<?php xten_post_thumbnail( array(957,536) ); ?>
					<div class="featured-image-mask">
						<h1 class="entry-title"><?php echo $get_the_title; ?></h1>
					</div>
				</div><!-- featured-image -->
				<?php
			endif;
		else: // ! if ( has_post_thumbnail() ) :

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta xten-highlight-font">
					<?php
					if ( is_singular() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					endif;
					?>
					<div class="post-date">
						<?php
							xten_posted_on();
						?>
					</div><!-- .post-date -->
				</div><!-- .entry-meta -->
				<?php
			endif;

		endif; // endif ( has_post_thumbnail() ) :
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'xten' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'xten' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer xten-highlight-font">
		<div class="post-category">
			<?php xten_post_categories(); ?>
		</div><!-- .post-category -->
		<?php
		xten_edit_post_link();
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
if ( is_singular() && ! is_page_template( 'page-templates/page-with-sidebar-template.php' ) ) :
	the_post_navigation(
		array(
			'prev_text'          => __( '<i class="fas fa-arrow-left"></i> %title' ),
			'next_text'          => __( '%title <i class="fas fa-arrow-right"></i>' ),
			'screen_reader_text' => __( 'Posts navigation' ),
		)
	);

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endif;
