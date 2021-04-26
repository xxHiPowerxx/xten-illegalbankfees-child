<?php
/**
 * Template-Part for displaying Category Archives. 
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */
$queried_object      = get_queried_object();
$category_id         = $queried_object->term_id;
$include_description = get_field( 'include_description', $queried_object );

// Get Public Post Types.
$post_type_args = array(
	'public' => true,
);
$post_types = get_post_types($post_type_args);
// Remove page, and attachment.
unset($post_types['page']);
unset($post_types['attachment']);

// Ensure that Investigations is first in the in the sort order.
// ISSUE: post_type === 'post' does not work on Staging when order => 'ASC' on Wordpress 5.6.
$args = array(
	'category'  => $category_id,
	'post_type' => $post_types,
	// 'order'     => 'ASC',
	// 'orderby'   => 'type',
);
$posts               = get_posts($args);
// Posts Investigations First.
$posts_i_f           = array();

// TODO: After updating Local and Staging environment to Wordpress 5.7+
// Attempt to use 'order' and 'orderby' in the get_posts() $args above.
// For now, use this brute-force method.
// Move Investigations to beginning.
foreach ( $posts as $key=>$post ) :
	if ( $post->post_type === 'investigations' ) :
		$posts_i_f[$key] = $post;
		unset($posts[$key]);
	endif;
endforeach;
$posts = $posts_i_f + $posts;
$querried_post_types = array();
foreach ( $posts as $post ) :
	$this_post_type = $post->post_type;
	if ( ! in_array( $this_post_type, $querried_post_types ) ) :
		array_push( $querried_post_types, $this_post_type );
	endif;
endforeach;
$multiple_post_types = count($querried_post_types) > 1 ? true : false;
$collapseClass       = $multiple_post_types ? 'collapse' : null;

foreach ( $querried_post_types as $post_type ) :
	$post_type_object = get_post_type_object($post_type);
	$post_type_name   = esc_attr( $post_type_object->labels->name );
	$archive_id       = 'archive-' . $post_type;
	?>
	<div class="archive-wrapper">
		<?php
		if ( $multiple_post_types ) :
			if ( $post_type_name === 'Posts' ) :
				$posts_page_title = esc_attr( get_the_title( get_option('page_for_posts', true) ) );
				if ( $posts_page_title ) :
					$post_type_name = $posts_page_title;
				endif;
			endif;
			?>
			<div id="<?php echo $post_type_name ?>-title" class="ctnr-archive-title xten-accordion page-header content-area card-style collapsed display-flex justify-content-between align-items-center" data-toggle="collapse" aria-controls="<?php echo $archive_id; ?>" data-target="#<?php echo $archive_id; ?>" aria-expanded="false" aria-label="Toggle Archive" tabindex="0">
				<h2 class="archive-title page-title"><?php echo $post_type_name; ?></h2>
				<span class="collapse-control-indicator fa fa-chevron-down"></span>
			</div>
			<?php
		endif; // endif ( $multiple_post_types ) :
		$args = array(
			'category'  => $category_id,
			'post_type' => $post_type,
		);
		$posts_of_type = query_posts('cat='. $category_id . '&post_type='. $post_type);
		if ( have_posts( $posts_of_type ) ) :
			?>

			<div id="<?php echo $archive_id; ?>" class="posts-list archive-container display-flex flex-row flex-wrap align-items-stretch <?php echo $collapseClass; ?>">
				<?php

				/* Start the Loop */
				while ( have_posts($posts_of_type) ) :
					?>
					<div class="article-container col-md-6">
						<?php
						the_post($posts_of_type);

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
				wp_reset_query();
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

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
	</div><!-- /.archive-wrapper -->
	<?php
endforeach;
