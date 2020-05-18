<?php
/**
 * Modal which will pull in whatever content is in the sidebar.
 *
 * @package xten
 */

// Modal which will pull in whatever content is in the sidebar
?>
<div class="modal fade xten-modal" id="sidebar-modal" tabindex="-1" role="dialog" aria-labelledby="Contact Form" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			<?php include( get_template_directory() . '/sidebar.php'); ?>
		</div>
	</div>
</div><!-- /#sidebar-modal -->

<?php 
$title    = get_the_title();
$category_title;
$category = get_the_category();
if ( isset( $category ) ) :
	$category_title = get_the_category()[0]->name !== 'uncategorized' ?
										get_the_category()[0]->name :
										null;
endif;

// Modal that displays if User Qualifies.
$qualifying_popup_content = get_field( 'qualifying_popup_content_override', false, false );
$qualifying_popup_content = $qualifying_popup_content ?
														wp_kses_post( $qualifying_popup_content ) :
														wp_kses_post( get_field( 'global_qualifying_popup_content', 'option', false ) );
if ( $qualifying_popup_content ) :
	$qualifying_popup_content = str_replace('${title}', $title, $qualifying_popup_content );
	$qualifying_popup_content = str_replace('${category}', $category_title, $qualifying_popup_content );
	$qualifying_popup_title   = get_field( 'qualifying_popup_title_override' );
	$qualifying_popup_title   = $qualifying_popup_title ?
															esc_attr( $qualifying_popup_title ) :
															esc_attr( get_field( 'global_qualifying_popup_title', 'option' ) );
	?>
	<div class="modal fade xten-modal" id="contact-qualify-modal" tabindex="-1" role="dialog" aria-labelledby="Contact Form" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<header class="modal-header xten-theme-color-bg">
					<?php if ( $qualifying_popup_title ) : ?>
						<h2 class="modal-title"><?php echo $qualifying_popup_title ?></h2>
					<?php endif; ?>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</header>
				<div class="modal-body xten-content">
					<?php echo $qualifying_popup_content; ?>
				</div>
			</div>
		</div>
	</div><!-- /#contact-qualify-modal -->
	<?php
endif; // endif ( $qualifying_popup_content ) :

// Modal that displays if User Does Not Qualify.
$rejection_popup_content = get_field( 'rejection_popup_content_override', false, false );
$rejection_popup_content = $rejection_popup_content ?
													 wp_kses_post( $rejection_popup_content ) :
													 wp_kses_post( get_field( 'global_rejection_popup_content', 'option', false ) );
if ( $rejection_popup_content ) :
	$rejection_popup_content = str_replace('${title}', $title, $rejection_popup_content );
	$rejection_popup_content = str_replace('${category}', $category_title, $rejection_popup_content );
	$rejection_popup_title   = get_field( 'rejection_popup_title_override' );
	$rejection_popup_title   = $rejection_popup_title ?
														 esc_attr( $rejection_popup_title ) :
														 esc_attr( get_field( 'global_rejection_popup_title', 'option' ) );
	?>
	<div class="modal fade xten-modal" id="contact-rejection-modal" tabindex="-1" role="dialog" aria-labelledby="Contact Form" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<header class="modal-header xten-theme-color-bg">
					<?php if ( $rejection_popup_title ) : ?>
						<h2 class="modal-title"><?php echo $rejection_popup_title ?></h2>
					<?php endif; ?>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</header>
				<div class="modal-body xten-content">
					<?php echo $rejection_popup_content; ?>
				</div>
			</div>
		</div>
	</div><!-- /#contact-rejection-modal -->
	<?php
endif; // endif ( $rejection_popup_content ) :