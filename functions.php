<?php
/*This file is part of the XTen Child Theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function enqueue_child_styles() {
	$parent_style = 'parent-style';
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array( 'xten-vendor-bootstrap-css' ) );
		wp_enqueue_style(
			'child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( $parent_style, 'xten-base-style' ),
			filemtime( get_stylesheet_directory() . '/style.css' ) 
		);

		// Register Styles
		$child_theme_css_path = '/assets/css/child-theme.min.css';
		wp_register_style( 'child-theme-css', get_stylesheet_directory_uri() . $child_theme_css_path, array( 'xten-base-style', 'xten-standard-header-css' ), filemtime( get_stylesheet_directory() . $child_theme_css_path ), 'all' );

		// Register Scripts
		$child_theme_js_path = '/assets/js/child-theme.min.js';
		wp_register_script( 'child-theme-js', get_stylesheet_directory_uri() . $child_theme_js_path, array('jquery'), filemtime( get_stylesheet_directory() . $child_theme_js_path ), true );

		// Enqueue Styles
		wp_enqueue_style( 'child-theme-css' );

		// Enqueue Scripts
		wp_enqueue_script( 'child-theme-js');

}
add_action( 'wp_enqueue_scripts', 'enqueue_child_styles' );
	
// IF ACF-JSON is a requirement create the acf-json folder and uncoment the following
// Load fields.
add_filter('acf/settings/load_json', 'child_acf_json_load_point');

function child_acf_json_load_point( $paths ) {

    // remove original path (optional)
    // unset($paths[0]);


    // append path
    $paths[] = get_stylesheet_directory() . '/acf/acf-json';


    // return
    return $paths;

}

/**
 * Get Option from Site Settings Page and save ACF to Child if Set.
 * Check to see if xten Save fields file exsists and adds save point if it does.
 * 
 */
function save_acf_fields_to_child_theme() {
	$save_acf_fields_to_child_theme = get_field('save_acf_fields_to_child_theme', 'options');
	// If not set, default to true.
	$save_acf_fields_to_child_theme = $save_acf_fields_to_child_theme !== null ? $save_acf_fields_to_child_theme : true;
	$select_where_to_save_acf_field_groups = get_field('select_where_to_save_acf_field_groups', 'options');
	if (
		$select_where_to_save_acf_field_groups === 'child' ||
		(
			$select_where_to_save_acf_field_groups === null &&
			$save_acf_fields_to_child_theme
		)
	) :
		$save_acf_fields = get_stylesheet_directory() . '/acf/save-acf-fields.php';
		if ( file_exists( $save_acf_fields ) ) {
			require $save_acf_fields;
		}
	endif;
}
add_action( 'acf/init', 'save_acf_fields_to_child_theme' );

/* for Contact-Form-7 */
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Custom Post Types.
 */
require get_stylesheet_directory() . '/inc/custom-post-types.php';



/**
 * Inline Styles.
 */
// require get_stylesheet_directory() . '/inc/xten-child-inline-styles.php';

add_action('wp_head','my_analytics', 20);
function my_analytics() {
?> 
	
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NPJ2CBG');</script>
<!-- End Google Tag Manager -->

<?php
}

add_action('__before_header','tag_manager2', 20);
function tag_manager2(){
?>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NPJ2CBG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
}

/**
 * Require Widgets File for custom widgets.
 */
require get_stylesheet_directory() . '/inc/widgets.php';