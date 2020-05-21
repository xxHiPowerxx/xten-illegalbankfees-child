<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

$get_the_title = wp_kses_post( get_field( 'page_heading_override', false, false ) );
if ( ! $get_the_title ) :
	$title    = get_the_title();
	$post_categories = get_the_category();
	foreach ( $post_categories as $post_category ) :
		$default_investigation_heading = wp_kses_post( get_field( 'default_investigation_heading', $post_category, false ) );
		if ($default_investigation_heading) :
			break;
		endif;

	endforeach;
	$get_the_title = $default_investigation_heading;
	$get_the_title = str_replace('${title}', $title, $get_the_title );
endif;
if ( ! $get_the_title ) :
	$get_the_title = '<h1 class="entry-title">' . get_the_title() . '</h1>';
endif;

$featured_image_cta_button_text = esc_attr( get_field('featured_image_cta_button_text') );

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
						<div class="featured-image-mask-inner">
							<?php
							echo $get_the_title;
							if ( $featured_image_cta_button_text ) :
								?>
								<button data-toggle="modal" data-target="#sidebar-modal" type="button" class="btn btn-theme-style xten-btn theme-style-white xten-mobile-menu-inactive-hide xten-mobile-menu-active-show"><?php echo $featured_image_cta_button_text; ?></button>
							<?php endif; ?>
						</div>
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
						<?php echo xten_posted_on(); ?>
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
		<?php if ( ! empty( get_the_category_list() ) ) : ?>
			<div class="post-category">
				<h5 class="post-category-title">Category:</h5>
				<?php xten_post_categories(); ?>
			</div><!-- .post-category -->
			<?php
			endif; // endif ( ! empty( get_the_category_list() ) ) :
		xten_edit_post_link();
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
if (
	is_singular() &&
	! is_page_template( 'page-templates/page-with-sidebar-template.php' ) 
) :
	$post_type_name = esc_attr( get_post_type_object( get_post_type() )->labels->singular_name );
	the_post_navigation(
		array(
			'prev_text'          => __( '<div class="nav-link-label"><i class="nav-link-label-icon fas fa-arrow-left"></i> <span class="nav-link-label-text">Previous ' . $post_type_name . '</span></div><div class="ctnr-nav-title"><span class="nav-title">%title</span></div>' ),
			'next_text'          => __( '<div class="nav-link-label"><span class="nav-link-label-text">Next ' . $post_type_name . '</span> <i class="nav-link-label-icon fas fa-arrow-right"></i></div><div class="ctnr-nav-title"><span class="nav-title">%title</span></div>' ),
			'screen_reader_text' => __( 'Posts navigation' ),
		)
	);

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endif;
