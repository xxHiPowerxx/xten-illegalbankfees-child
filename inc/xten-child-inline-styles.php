<?php

/**
 * Process Inline CSS
 */
function process_xten_child_inline_css() {

	// Get the Stylesheet Directory.
	$parent_theme_dir = get_template_directory_uri();
	$child_theme_dir = get_stylesheet_directory_uri();

	$heading_default_font = json_decode( '{"heading":"Bebas Neue", "heading_fallback":"Arial,sans-serif"}' );
	$body_default_font    = json_decode( '{"body":"opensans", "body_fallback":"Arial,sans-serif"}' );

	// /Header Mobile Nav.
	// Begin Style Tag.
	$styles     = '';
	$bodyStyles = '';

	// Load Fonts Used.
	// Theme Heading Default Font.
	$headingPath             = $heading_default_font->heading;
	$heading_font_w_fallback = $heading_default_font->heading . ',' . $heading_default_font->heading_fallback;
	$bodyPath                = $body_default_font->body;
	$body_font_w_fallback    = $body_default_font->body . ',' . $body_default_font->body_fallback;


	$fontWeight = '-semibold.ttf';

	$googleFontsURL = 'https://fonts.googleapis.com/css?family=Bebas+Neue&display=swap';

	$bodyStyles .= '@font-face{' .
		'font-family:' . $body_default_font->body . ';' .
		'font-weight: normal;' .
		'src:url(' . $parent_theme_dir . '/assets/fonts/' . $bodyPath . '/' . $bodyPath . '-light.ttf' . ');' .
	'}';

	$bodyStyles .= '@font-face{' .
		'font-family:' . $body_default_font->body . ';' .
		'font-weight: bold;' .
		'src:url(' . $parent_theme_dir . '/assets/fonts/' . $bodyPath . '/' . $bodyPath . $fontWeight . ');' .
	'}';

	// Assign Styles.
	$styles .= 'h1,.section-heading{' .
		'font-family:' . $heading_font_w_fallback . ';' .
	'}' .
	$bodyStyles .= 'body,button,input,optgroup,select,textarea{' .
		'font-family:' . $body_font_w_fallback . ';' .
		'}' .
		'h2,h3,h4,h5,h6,.xten-highlight-font{' .
			'font-family:' . $heading_font_w_fallback . ';' .
		'}' .
	'}';

	$load_splash = '#load-splash.hiding .load-splash-inner .custom-logo{' .
		'-webkit-filter:drop-shadow(0 .1em .1em rgba(32, 32, 32, .55));' .
			'filter:drop-shadow(0 .1em .1em rgba(32, 32, 32, .55))' .
		'}' .
		'#load-splash.hiding .load-splash-inner .custom-logo [style*=fill][style*=fff]{' .
			'fill:#364766!important' .
		'}' .
		'#load-splash.hiding .load-splash-inner .custom-logo [style*=fill][style*=fff]:last-child{' .
			'stroke:#fff' .
		'}' .
		'#load-splash.hiding .load-splash-inner .custom-logo [style*=stroke][style*=fill]:first-child{' .
			'stroke:#364766!important;' .
			'fill:#fff!important' .
		'}';
	
	wp_enqueue_style( 'xten-child-google-font', $googleFontsURL );
	wp_register_style( 'xten-child-inline-style', false );
	wp_enqueue_style( 'xten-child-inline-style', '', array('xten-content-css', 'xten-inline-style') );
	wp_add_inline_style( 'xten-child-inline-style', $styles );
	wp_add_inline_style( 'xten-child-inline-style', $bodyStyles );
	wp_add_inline_style( 'xten-child-inline-style', $load_splash );
}

add_action( 'wp_enqueue_scripts', 'process_xten_child_inline_css', 99 );
