<?php
/**
 * Collection of Shortcodes.
 *
 * @package xten
 */

/**
 * Form Group shortcode for wrapping Label and Input Field.
 *
 * @param array $atts attributes from shortcode.
 */
function form_group_shortcode_func( $atts, $content = null ) {
	
	$a = shortcode_atts( array(
		'specifyOtherParent' => false,
	), $atts );
	$specify_other_parent = null;
	$specify_other_input_group = null;

	if ( $a['specifyOtherParent'] ) :
		$specify_other_parent = 'specifyOtherParent';
		ob_start();
		?>
		<div class="specify-other-input-group">
			<input type="text" class="form-control specifyOtherTextInput" placeholder="Specify Other Financial Institution" />
			<button type="button" aria-label="Add Financial Institution" class="btn btn-theme-style xten-btn xten-btn-sm xten-btn theme-style-dark addOtherOption" title="Add Financial Institution"><span class="fas fa-plus"></span></button>
		</div>
		<?php
		$specify_other_input_group = ob_get_clean();
	endif;
	
	$return_result = '<div class="form-group' . $specifyOtherParent . '">' . $content . $specify_other_input_group . '</div>';

	return $return_result;
}
add_shortcode( 'form_group', 'form_group_shortcode_func' );

/**
 * Form Multi-State shortcode for wrapping Input Field Groups.
 *
 * @param array $atts attributes from shortcode.
 */
function form_state_shortcode_func( $atts, $content = null ) {
	
	$a = shortcode_atts( array(
		'sizer'             => null,
		'submit_form_state' => null,
	), $atts );

	$return_result = array(
		'form_state_atts' => $a,
		'form_state_content' => $content,
	);

	return $return_result;
}
add_shortcode( 'form_state', 'form_state_shortcode_func' );

/**
 * Form Multi-State shortcode for wrapping Input Field Groups.
 *
 * @param array $atts attributes from shortcode.
 */
function multi_state_form_shortcode_func( $atts, $content = null ) {
	
	$ms_atts = shortcode_atts( array(
		'form_states'       => array(),
		'data-parent'       => null,
		'submit_shortcode' => null,
	), $atts );
	if ( ! empty( $ms_atts['form_states'] ) ) :
		$form_states = explode (",", $content);
	endif;

	$return_result = '';
	$form_states_count = count( $form_states );

	foreach ( $form_states as $index=>$form_state ) :

		$form_state_id = 'formState-' . $index;
		$fs_atts = $form_state['form_state_atts'];
		$data_parent = $ms_atts['data-parent'] ? 'data-parent' . $ms_atts['data-parent'] : null;
		$sizer_class = null;
		$size_ref_class = null;
		if ( $fs_atts['sizer'] ) :
			$sizer_class = ' sizer';
			$size_ref_class = ' sizeRef';
		endif;
		$first_form_state = $index === 0;
		$show_class = $first_form_state ? ' show' : null;
		$form_state_classes = $sizer_class . $show_class;
		$form_state_control_next = null;
		// Check if Form State is designated for submit button.
		if ( $fs_atts['submit_form_state'] ) :
			// If is Form State designated for submit button
			// use shortcode provided in "submit_shorcode" attr on Multi State Form.
			$form_state_control_next = $ms_atts['submit_shortcode'];
		elseif( $form_states_count - 1 > $index ) :
			ob_start();
			$data_target_next = 'formState-' . ( $index + 1 );
			?>
			<button class="btn btn-theme-style xten-btn theme-style-dark formState-control-next" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr( $data_target_next ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $data_target_next ); ?>">Next</button>
			<?php
			$form_state_control_next = ob_get_clean();
		endif; // endif ( $fs_atts['submit_form_state'] ) :

		$form_state_control_prev = null;
		if( ! $first_form_state ) :
			ob_start();
			$data_target_prev = 'formState-' . ( $index - 1 );
			?>
			<button class="btn btn-theme-style xten-btn theme-style-dark formState-control-prev" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr( $data_target_prev ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $data_target_prev ); ?>">Back</button>
			<?php
			$form_state_control_prev = ob_get_clean();
		endif;

		$return_result .= `<div id="<?php echo esc_attr( $form_state_id ); ?>" class="formState collapse` . $form_state_classes . `" $data_parent>
			<div class="formState-inner">
				<div class="formState-content">
					<div class="formState-content-inner` . $size_ref_class . `">
						` . $form_state['form_state_content'] . `
					</div>
				</div>
				<div class="formState-controls` . $size_ref_class . `">
					` . $form_state_control_prev . `
					` . $form_state_control_next . `
				</div>
			</div>
		</div>`;
	endforeach; // endforeach ( $form_states as $index=>$form_state ) :

	return $return_result;
}
add_shortcode( 'multi_state_form', 'multi_state_form_shortcode_func' );