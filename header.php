<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xten
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> class="no-js xten-theme-color-bg">
	<head>
		<!--insert meta tags  -->
		<?php require_once get_template_directory() . '/inc/meta-tags.php'; ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class('xten-theme-color-bg'); ?> data-spy="scroll" data-target="#xten-scroll-nav" data-offset="100">

		<div id="load-splash" class="loading xten-theme-color-bg">
			<div class="load-splash-inner">
				<?php echo $GLOBALS['xten-site-logo']; ?>
			</div>
		</div>
		<div id="page" class="site">
			<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'xten' ); ?></a>
			<?php require_once get_stylesheet_directory() . '/inc/header/header-menu.php'; ?>
			<div id="page-wrapper" class="page-wrapper">
				<div class="content-wrapper <?php echo esc_html( $size_header_pad ); ?>">
					<?php require_once get_template_directory() .  '/inc/alert-content.php'; ?>
