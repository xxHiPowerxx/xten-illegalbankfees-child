<?php
/**
 * Page for creating custom widgets.
 *
 * @package xten
 */

// Register and load the widget
function xten_load_widget() {
	register_widget( 'contact_forms_widget' );
}
add_action( 'widgets_init', 'xten_load_widget' );

// Creating the widget 
class contact_forms_widget extends WP_Widget {

	function __construct() {
		parent::__construct(

			// Base ID of your widget
			'contact_forms_widget', 

			// Widget name will appear in UI
			__('Contact Forms', 'xten'), 

			// Widget description
			array( 'description' => __( 'Associative Contact Forms based on Category', 'xten' ), ) 
		);
	}

	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) :
			$title = $instance[ 'title' ];
		else :
			$title = __( 'New title', 'xten' );
		endif;
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
		// The rest of the backend form will be handled by an ACF Field Group.
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {
		$widget_id   = $args['widget_id'];
		$title       = apply_filters( 'widget_title', $instance['title'] );

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];

		if ( ! empty( $title ) ) :
			echo $args['before_title'] . $title . $args['after_title'];
		endif;

		// Get Contact Form requested.
		$get_field_param                = 'widget_' . $widget_id;
		$contact_form_category_repeater = get_field( 'contact_form_category_repeater', $get_field_param );

		if ( isset( $contact_form_category_repeater ) ) :
			$page_category_id = get_the_category()[0]->term_id;
			// Set First Associated Contact Form as Default.
			$found_contact_form = $contact_form_category_repeater[0];
			// Then Loop through and find Correct Associated Contact Form if exists.
			foreach ( $contact_form_category_repeater as $contact_form ) :
				$form_categories   = $contact_form['category'];
				if ( $form_categories ) :
					$matching_category = array_search( $page_category_id, $form_categories );
				endif;
				if ( $matching_category !== false ) :
					$found_contact_form = $contact_form;
					break; // Stop the loop.
				endif;
			endforeach; // endforeach ( $contact_form_category_repeater as $contact_form ) :
			$used_contact_form   = $found_contact_form['contact_form'];
			$contact_form_id     = $used_contact_form->ID;
			$contact_form_title  = $used_contact_form->post_title;
			$contact_form_output = do_shortcode( '[contact-form-7 id="' . $contact_form_id . '" title="' . $contact_form_title . '"]' );
			echo __( $contact_form_output, 'xten' );
		endif; // endif ( have_rows( $contact_form_category_repeater ) ) :

		echo $args['after_widget'];
	}
} // /class contact_forms_widget extends WP_Widget {