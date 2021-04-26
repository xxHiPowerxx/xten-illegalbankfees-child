<?php
/**
 * Template-Part for displaying Custom Post Type Archives. 
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

$queried_object      = get_queried_object();
$category_id         = $queried_object->term_id;
$include_description = get_field( 'include_description', $queried_object );
$no_posts_found      = false;
// Get Public Post Types.
$post_type_args = array(
	'public' => true,
);
//// $post_types = get_post_types($post_type_args);

// Remove page, and attachment.
//// unset($post_types['page']);
//// unset($post_types['attachment']);

$args = array(
'category'  => $category_id,
'post_type' => $post_types,
);
//// $posts               = get_posts($args);
$posts = get_categories( array( 'post-type=' . $queried_object->name ) );
$querried_post_types = array();
foreach ( $posts as $post ) :
//	// $this_post_type = $post->post_type;
	$this_post_type = $post->term_id;
	if ( ! in_array( $this_post_type, $querried_post_types ) ) :
		array_push( $querried_post_types, $this_post_type );
	endif;
endforeach;
$multiple_post_types = count($querried_post_types) > 1 ? true : false;
$collapseClass       = $multiple_post_types ? 'collapse' : null;

foreach ( $querried_post_types as $post_type ) :
	//	// $post_type_object = get_post_type_object($post_type);
	// $querried_post_types is just a string, we need it to be the TERM ID.
	$posts_of_type = query_posts( 'cat='. $post_type . '&post_type='. $queried_object->name );
	if ( have_posts( $posts_of_type ) ) :
		foreach ( $posts as $post ) :
			if ( $post->term_id === $post_type ) :
				$post_type_object = $post;
				break;
			endif;
		endforeach;
	//	// $post_type_name   = esc_attr( $post_type_object->labels->name );
		$post_type_name   = esc_attr( $post_type_object->name );
		$archive_id       = preg_replace("/[^A-Za-z0-9-_]/", '', str_replace( ' ', '-', strtolower( 'archive-' . $post_type_name ) ) );
		?>

		<div id="<?php echo $archive_id . '-wrapper'; ?>" class="archive-wrapper">
			<?php
			if ( $multiple_post_types ) :
				if ( $post_type_name === 'Posts' ) :
					$posts_page_title = esc_attr( get_the_title( get_option('page_for_posts', true) ) );
					if ( $posts_page_title ) :
						$post_type_name = $posts_page_title;
					endif;
				endif;
				?>
				<div id="<?php echo $archive_id ?>-title" class="ctnr-archive-title xten-accordion page-header content-area card-style collapsed display-flex justify-content-between align-items-center" data-toggle="collapse" aria-controls="<?php echo $archive_id; ?>" data-target="#<?php echo $archive_id; ?>" aria-expanded="false" aria-label="Toggle Archive" tabindex="0">
					<h2 class="archive-title page-title"><?php echo $post_type_name; ?></h2>
					<span class="collapse-control-indicator fa fa-chevron-down"></span>
				</div>
				<?php
			endif; // endif ( $multiple_post_types ) :
			?>

			<div id="<?php echo $archive_id; ?>" class="posts-list archive-container display-flex flex-row flex-wrap align-items-stretch <?php echo $collapseClass; ?>">
				<?php

				/* Start the Loop */
				while ( have_posts($posts_of_type) ) :
					/*var_dump($post);*/
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

			?>
		</div><!-- /.archive-wrapper -->
		<?php
	endif; // endif ( have_posts( $posts_of_type ) ) :
endforeach;
