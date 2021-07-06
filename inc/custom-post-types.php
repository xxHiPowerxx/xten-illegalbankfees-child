<?php
/**
 * File included in functions for Creating Custom Post Types
 *
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * @package bolt-on
 */

/**
 * A Function that will render our custom post types.
 */
function custom_post_types() {
	/**
	 * Utility Function that creates a single post type.
	 * This way we don't have to rewrite the code more than once,
	 * but simply pass our arguments into the function.
	 */
	function create_custom_post_type (
		$post_singular,
		$post_plural      = null,
		$post_description = null,
		$hierarchical     = false,
		$icon             = 'admin-post',
		$settings_page    = false
	) {
		$post_plural      = $post_plural === null ?
												$post_singular . 's' :
												$post_plural;
		$post_description = $post_description === null ?
												$post_plural :
												$post_description;
		$menu_icon        = 'dashicons-' . $icon;
		$post_handle      = str_replace( ' ','-', strtolower( $post_plural ) );
		$theme            = 'bolt-on';
		// Set UI labels for Custom Post Type
		$post_labels = array(
			'name'                => _x( $post_plural, 'Post Type General Name', $theme ),
			'singular_name'       => _x( $post_singular, 'Post Type Singular Name', $theme ),
			'menu_name'           => __( $post_plural, $theme ),
			'parent_item_colon'   => __( 'Parent ' . $post_singular, $theme ),
			'all_items'           => __( 'All ' . $post_plural, $theme ),
			'view_item'           => __( 'View ' . $post_singular, $theme ),
			'add_new_item'        => __( 'Add New ' . $post_singular, $theme ),
			'add_new'             => __( 'Add New', $theme ),
			'edit_item'           => __( 'Edit ' . $post_singular, $theme ),
			'update_item'         => __( 'Update ' . $post_singular, $theme ),
			'search_items'        => __( 'Search ' . $post_singular, $theme ),
			'not_found'           => __( 'Not Found', $theme ),
			'not_found_in_trash'  => __( 'Not found in Trash', $theme ),
		);
		// Set other options for Custom Post Type
		$args = array(
			'label'               => __( $post_plural, $theme ),
			'description'         => __( $post_description, $theme ),
			'labels'              => $post_labels,
			// Features this CPT supports in Post Editor
			'supports'            => array(
																'title',
																'editor',
																'excerpt',
																'thumbnail',
																'revisions',
																'custom-fields',
																'page-attributes',
																'post-formats'
															),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( 'category' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/ 
			'hierarchical'        => $hierarchical,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => $menu_icon,
			'rewrite'             => array(
															 	'slug'       => $post_handle,
															 	'with_front' => false,
															 ),
		); // /$args;
		
		/**
		 * Create Custom Options Page for Post Type.
		 */
		if ( $settings_page || function_exists( 'acf_add_options_page' ) ) :
			acf_add_options_sub_page(
				array(
					'page_title'  => $post_plural . ' Settings',
					'menu_title'  => $post_plural . ' Settings',
					'parent_slug' => 'edit.php?post_type=' . $post_handle,
				)
			);
		endif;

		// Registering your Custom Post Type
		return register_post_type( $post_handle, $args );
	}

	// Get ACF Field created for Investigation Descriptions.
	$investigation_description = get_field( 'investigations_description', 'option' ) ? : 'Investigations of Banks, Organizations, etc...';
	// Use our utility function to render different Custom Post Types.
	create_custom_post_type(
		'Investigation',
		null,
		$investigation_description,
		false,
		'search',
		true
	);

}
	 
	/**
	 *  Hook into the 'init' action so that the function
	 * Containing our post type registration is not
	 * unnecessarily executed.
	 */
	 
	add_action( 'init', 'custom_post_types', 0 );
