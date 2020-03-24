<?php
/**
 * Template Name: Page With Sidebar
 *
 * @package     XTen
 */

get_header();

$sidebar_location = get_theme_mod( 'sidebar_location', 'sidebar_right' );
$column           = '';
if ( 'none' !== $sidebar_location ) {
	$column = '-xl-8';
};
?>
<div class="sizeContent container container-ext main-container">
	<div class="row">
		<div class="col<?php echo esc_attr( $column ); ?> order-lg-1" id="primary">
			<main id="main" class="site-main single-page">

				<?php
				while ( have_posts() ) :
					the_post();

					/*
					* Include the component stylesheet for the content.
					* This call runs only once on index and archive pages.
					* At some point, override functionality should be built in similar to the template part below.
					*/
					wp_print_styles( array( 'xten-content-css' ) ); // Note: If this was already done it will be skipped.

					get_template_part( 'template-parts/content', 'post' );

				endwhile; // End of the loop.
				?>

			</main><!-- #primary -->
		</div> <!-- end column -->
		<?php
		/**
		 * Customizer Ordered sidebar.
		 */
		require get_template_directory() . '/inc/sidebar-display-order.php';
		?>
	</div> <!-- end row -->
</div> <!-- end sizeContent -->

<?php
include get_stylesheet_directory() . '/inc/sidebar-modal.php';
get_footer();