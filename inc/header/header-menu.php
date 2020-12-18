<?php
$site_name                 = esc_attr( get_bloginfo() );
$menu_name                 = 'primary';
$locations                 = get_nav_menu_locations();
$menu_items                = null;
$mobile_nav_breakpoint     = $GLOBALS['mobile_nav_breakpoint'];


if ( $locations && isset( $locations[ $menu_name ] ) ) :
	$primary_menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
	if ( $primary_menu ) :
		$menu_items = wp_get_nav_menu_items( $primary_menu->term_id );
		endif;
	endif;

	$mobile_menu_bottom_active = is_active_sidebar( 'mobile-menu-bottom' );

	// Store result in variable to be later used to validate .mobile-navigation.
?>
<div id="menu-wrapper" class="<?php echo esc_attr( $header_selection_class ); ?>" data-mobile-nav-breakpoint="<?php echo esc_attr( $mobile_nav_breakpoint ); ?>">
	<div id="mobile-sidebar" class="mobile-sidebar collapse"> <!-- Mobile Nav -->
		<?php
		$is_mobile_gobal_nav = true;

		// Main Nav Mobile Accordion.
		?>
		<div class="mobile-sidebar-top">
			<div class="site-branding">
				<?php	$home_url = esc_url( home_url( '/' ) ); ?>
				<a class="custom-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url" title="<?php echo esc_attr( $site_name ); ?>"><span class="hide-me">Home Link</span>
					<div class="ctnr-custom-logo <?php echo $GLOBALS['xten-using-child-logo'] ? 'child-logo' : ''; ?>">
						<?php echo $GLOBALS['xten-site-logo'] ?>
					</div>
				</a>
			</div><!-- /.site-branding -->
			<button id="mobile-nav-close" class="mobile-toggler" type="button" data-toggle="collapse" aria-controls="mobile-sidebar" aria-expanded="false" aria-label="Toggle Navigation" tabindex="0">
				<div class="mobile-toggler-icon">
					<i class="fas fa-times"></i>
				</div>
			</button>
		</div><!-- /.mobile-sidebar-top -->
		<div class="mobile-main-navigation-wrapper">
			<div class="collapse show" id="mobile-main-navigation">
				<!-- Mobile Search -->
				<div class="mobile-search">
					<?php echo get_search_form(); ?>
				</div>

				<?php
				// Primary Nav.
				if ( $menu_items ) :
						wp_nav_menu(
							array(
								'theme_location' => $menu_name,
								'menu_id'        => 'mobile-menu',
								'container'      => 'ul',
								'depth'          => 2,
								'walker'         => new XTen_Walker(),
							)
						);
				endif;
				// Close Main Nav Mobile Accordion.
				?>

			</div>
		</div><!-- .mobile-main-navigation-wrapper -->

		<?php
		// Bottom Widget in mobile menu.
		if ( is_active_sidebar( 'mobile-menu-bottom' ) ) :
			?>
			<div class="mobile-menu-bottom">
				<?php dynamic_sidebar( 'mobile-menu-bottom' ); ?>
			</div>
			<?php
		endif;
		?>
	</div><!-- .mobile-sidebar -->

	<?php

	$size_header_pad = 'sizeHeaderPad';
	$size_header_ref = 'sizeHeaderRef';

	?>
	<div class="header-wrapper sizeHeaderRef">
		<?php require_once get_stylesheet_directory() . '/inc/header/desktop-menu.php'; ?>
	</div>
</div>
