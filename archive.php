<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

get_header(); ?>

<?php wp_print_styles( array( 'xten-archive-css' ) ); ?>
	<?php
	$sidebar_location = get_theme_mod( 'sidebar_location', 'sidebar_right' );
	$column           = '';
	if ( 'none' !== $sidebar_location ) {
		$column = '-xl-8';
	};
	?>
	<div class="sizeContent container container-ext main-container">
		<div class="row">
			<div class="col<?php echo esc_attr( $column ); ?> order-xl-1" id="primary">
				<main id="main" class="site-main archive-page">

					<?php
					/*
					* Include the component stylesheet for the content.
					* This call runs only once on index and archive pages.
					* At some point, override functionality should be built in similar to the template part below.
					*/
					wp_print_styles( array( 'xten-content-css' ) ); // Note: If this was already done it will be skipped.

					global $wp_query;
					$is_category = $wp_query->is_category;
					$is_custom_post_type = $wp_query->is_post_type_archive;

					// If page is a Category Archive OR is Investigation Archive, get the thumbnail.
					if ( $is_category || $is_custom_post_type ) :
						if ( ! function_exists( 'use_acf_field_h1' ) ) :
							function use_acf_field_h1() {
								$h1 = wp_kses_post( get_field( 'page_heading_override', false, false ) );
								return $h1;
							}
						endif;
						$get_the_title                  = use_acf_field_h1() === '' ? get_the_archive_title() : use_acf_field_h1();
						$featured_image_cta_button_text = esc_attr( get_field('featured_image_cta_button_text') );
						$archive_description           = get_the_archive_description();
						$has_archive_description_class = ! $archive_description ? ' no-description' : null;
						if ( $is_category ) :
							$archive_thumbnail = get_field( 'category_thumbnail', get_queried_object() );
						else :
							// For this to work programatically, the thumbnail field must be named 'post-type'_thumbnail.
							// EG: $post_type === 'products' Field Name = 'products_thumbnail';
							$post_type = $wp_query->query['post_type'];
							$thumbnail_handle = $post_type . '_thumbnail';

							$archive_thumbnail = get_field( $thumbnail_handle, 'option' );
						endif;
						?>
						<header class="page-header entry-header content-area card-style<?php echo $has_archive_description_class; ?>">
							<?php
							if ( $archive_thumbnail ) :
								$thumbnail_id  = $archive_thumbnail['ID'];
								$thumbnail_img = wp_get_attachment_image( 
									$thumbnail_id,
									array(957, null),
									false,
									array(
										'title' => single_cat_title( '', false )
									)
								);
								?>
								<div class="featured-image">
									<div class="post-thumbnail">
										<?php echo $thumbnail_img; ?>
									</div>
									<div class="featured-image-mask">
										<h1 class="page-title entry-title"><?php echo $get_the_title; ?></h1>
									</div>
								</div><!-- featured-image -->
							<?php
							else : // ! if ( $archive_thumbnail ) :
								?>
								<h1 class="page-title entry-title"><?php echo $get_the_title; ?></h1>
								<?php
							endif; // endif ( $archive_thumbnail ) :
							if ( $archive_description ) :
								 ?>
								<div class="archive-description">
									<?php echo $archive_description; ?>
								</div>
							<?php
							endif; // endif ( $archive_description ) :
							?>
						</header><!-- .entry-header -->

						<?php
					else :
						/* Display the appropriate header when required. */
						xten_index_header();
					endif;// endif ( $is_category || $is_investigations ) :

					/**
					 * Check if is page Category, and if so, get archive-category.php
					 */
					if ( $is_category ) :

						get_template_part( 'archive-category' );

					else : // else if ( ! $is_category ) :

						if ( have_posts() ) :
							?>

							<div class="archive-container d-flex flex-row flex-wrap align-items-stretch posts-list">
								<?php
								/* Start the Loop */
								while ( have_posts() ) :
									?>
									<div class="article-container col-md-6">
										<?php
										the_post();

										/*
										* Include the Post-Type-specific template for the content.
										* If you want to override this in a child theme, then include a file
										* called content-___.php (where ___ is the Post Type name) and that will be used instead.
										*/
										get_template_part( 'template-parts/content', 'archive-post' );
										?>
									</div><!-- .article-container -->
									<?php
								endwhile;
								?>
							</div>
							<?php

							the_posts_navigation(
								array(
									'prev_text'          => __( '<i class="fas fa-arrow-left"></i> Older posts', 'xten' ),
									'next_text'          => __( 'Newer posts <i class="fas fa-arrow-right"></i>', 'xten' ),
									'screen_reader_text' => __( 'Posts navigation', 'xten' ),
								)
							);

						else : // else if ( ! have_posts() ) :

							get_template_part( 'template-parts/content', 'none' );

						endif; // endif ( have_posts() ) :

					endif; // endif ( $is_category ) :
					?>

				</main><!-- #main -->
			</div> <!-- end column -->
			<?php
			/**
			 * Customizer Ordered sidebar.
			 */
			require get_template_directory() . '/inc/sidebar-display-order.php';
			?>
		</div> <!-- row -->
	</div><!-- end sizeContent -->
<?php

get_footer();
