<?php
/**
 * Standard Header included if Legacy Header is not Selected in Standard Header Options.
 *
 * @package xten
 */

wp_enqueue_style( 'xten-standard-header-css' );

$global_xten_header_file = $GLOBALS['global_xten_header_file'];
$site_name                 = $GLOBALS['department_name'];
$mobile_menu_breakpoint    = $GLOBALS['mobile_menu_breakpoint'];
$root_dir                  = get_template_directory_uri();
$font                      = 'roboto';
$style                     =
	'@font-face {' .
		'font-family:' . $font . ';' .
		'src: url(' . $root_dir . '/assets/fonts/' . $font . '/' . $font . '.ttf);' .
	'}' .
	'.main-navigation li,.xten-header{' .
		'font-family:roboto,helvetica,arial,sans-serif;' .
	'}' .
	'@media (min-width:' . $mobile_menu_breakpoint . 'px ){' .
		'.xten-standard-header .site-branding{' .
			'margin-right:6rem;' .
		'}' .
	'}';
wp_register_style( 'xten-standard-header-inline-style', false );
wp_enqueue_style( 'xten-standard-header-inline-style', '', 'xten-content-css' );
wp_add_inline_style( 'xten-standard-header-inline-style', $style );
?>
<div class="xten-menu">
	<?php
	$GLOBALS['new_xten_header_mobile'] = 'new_xten_header_main';
	$is_mobile_gobal_nav                 = false;
	?>
</div>
<header id="masthead" class="site-header new-site-header fixed-header">
	<div class="navbar" id="mainNav">
		<div class="header-container container container-ext">
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
			<nav id="nav-mega-menu" class="main-navigation desktop-navigation navbar-nav ml-auto" aria-label="<?php esc_attr_e( 'Main menu', 'xten' ); ?>">
				<?php
				$menu_name = 'primary';
				$locations = get_nav_menu_locations();

				if ( $locations && isset( $locations[ $menu_name ] ) && $locations[ $menu_name ] > 0 ) :
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'desktop-menu',
							'container'      => 'ul',
							'depth'          => 2,
							'walker'         => new XTen_Walker(),
						)
					);
				endif;
				?>
			</nav><!-- #site-navigation -->
			<?php

			// Get Customizer Setting for Search Icon.
			$main_nav_search = get_theme_mod( 'main_nav_search', true );
			if ( $main_nav_search ) :
				?>
				<button class="header-search-toggle" type="button" data-toggle="collapse" data-target="#header-search" aria-controls="header-search" aria-expanded="false" aria-label="Toggle search">
					<i class="fas fa-search"></i>
				</button>
			<?php	endif; ?>
		</div><!-- /.header-container -->
	</div><!-- /#mainNav -->
	<?php	if ( $main_nav_search ) : ?>
		<div class="collapse header-search" id="header-search">
			<div class="header-search-wrapper">
				<div class="container">
					<div class="row">
						<div class=" col-sm-12 col-lg-8 offset-lg-2">
							<?php echo get_search_form(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php	endif; ?>
</header><!-- #masthead -->
