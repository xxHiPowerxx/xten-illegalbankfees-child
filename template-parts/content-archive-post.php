<?php
/**
 * Template part for displaying a single archive-post in a collection of archive-posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'listed-post card-style content-area d-flex flex-column' ); ?>>
	<div class="featured-image">
		<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
			<?php
			if ( has_post_thumbnail() ) :
				the_post_thumbnail( 'archive-thumbnail' );
			elseif ( get_theme_mod( 'default_post_image' ) ) :
				echo wp_get_attachment_image( get_theme_mod( 'default_post_image' ), 'archive-thumbnail' );
			else :
				?>
				<?php
				$thumb_id = get_post_thumbnail_id( get_the_ID() );
				$alt      = esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) );
				?>
				<img src="<?php echo get_template_directory_uri(); ?>/images/426x240.png" class="attachment-archive-thumbnail" alt="<?php echo $alt; ?>" width="426" height="240" />
				<?php
			endif;
			?>
		</a>
	</div>
	<div class="post-body d-flex flex-column">

		<header class="entry-header">
			<?php

			if ( 'page' !== get_post_type() ) :
				?>
				<div class="entry-meta xten-highlight-font">
					<?php
					if ( is_singular() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h5 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' );
					endif;
					?>
					<div class="post-date">
						<?php echo xten_posted_on(); ?>
					</div>
				</div><!-- .entry-meta -->
				<?php
			endif;
			?>
		</header><!-- .entry-header -->
		<?php
		// Get Description Setting from Archive Category Setting.
		// Only exclude description if setting is NOT set to false.
		// It could be null or true otherwise.
		if ( get_post_type() === 'investigations' ) :
			$include_description = get_field( 'include_description_in_invesitgations_archive', 'option' );
		else :
			global $include_description;
		endif;
		if ( $include_description !== false ) :
			?>
			<div class="entry-content">
				<?php

				the_excerpt(
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
		<?php endif; // endif ( $include_description ) : ?>

		<footer class="entry-footer xten-highlight-font">
			<div class="cat-link-container d-flex flex-row justify-content-between">
				<div class="post-category">
					<h5 class="post-category-title">Category:</h5>
					<?php xten_post_categories(); ?>
				</div>
				<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>" title="<?php echo get_the_title(); ?>" class="post-link xten-theme-color-bg material-btn"><i class="fas fa-arrow-right"></i><span class="hide-me"><?php echo get_the_title(); ?></span></a>
			</div>
			<?php
			xten_edit_post_link();
			?>
		</footer><!-- .entry-footer -->

	</div>

</article><!-- #post-<?php the_ID(); ?> -->
