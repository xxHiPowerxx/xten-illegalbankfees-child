<?php
require get_template_directory() . '/inc/header/xten-site-logo-svg.php';
$site_name                 = $GLOBALS['department_name'];
$menu_name                 = 'primary';
$locations                 = get_nav_menu_locations();
$menu_items                = null;
$standard_header_selection = $GLOBALS['internet_or_xtenline'];
$header_selection_name     = str_replace( '_', '-', $standard_header_selection );
$header_selection_class    = 'xten-' . $header_selection_name;
$mobile_nav_breakpoint     = $GLOBALS['mobile_menu_breakpoint'];


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
	<div id="mobile-sidebar" class="mobile-sidebar mobile-sidebar-closed"> <!-- Mobile Nav -->
		<?php
		$is_mobile_gobal_nav = true;

		// Main Nav Mobile Accordion.
		?>
		<div class="primary-nav-wrapper">
			<div class="primary-nav-trigger">
				<div class="site-branding">
					<?php	$home_url = esc_url( home_url( '/' ) ); ?>
					<a class="custom-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url" title="<?php echo esc_attr( $site_name ); ?>"><span class="hide-me">Home Link</span>
						<div class="custom-logo <?php echo file_exists( get_stylesheet_directory() . '/header-logo.svg' ) ? 'child-logo' : ''; ?>">
							<?php
							if ( file_exists( get_stylesheet_directory() . '/header-logo.svg' ) ) :
								require get_stylesheet_directory() . '/header-logo.svg';
							else :
								$xten_header_logo_svg = $GLOBALS['xten-header-logo'];
								echo $xten_header_logo_svg;
								?>
								<?php if ( $site_name ) : ?>
									<span class="site-name"><?php echo esc_attr( $site_name ); ?> </span>
								<?php endif; ?>
								<?php
							endif;
							?>
						</div>
					</a>
				</div><!-- .site-branding -->
				<button class="btn mobile-main-nav-trigger mobile-nav-trigger" type="button" data-toggle="collapse" data-target="#mobile-main-navigation" aria-expanded="true" aria-controls="mobile-main-navigation">
					<div><span>MENU</span></div> <i class="fa fa-chevron-down"></i>
				</button>
			</div>

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
			</div>
		</div>

		<div class="mobile-translate"></div>

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