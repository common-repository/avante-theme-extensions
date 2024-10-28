<?php 
/**
 * WordPress Widget Format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Avante_FAQ_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {
      $widget_ops = array(
          'classname' => 'avante-faq-widget', 
          'description' => __('Add an FAQ to the FAQs section of the One-Page Template.', 'avante-lite')
      );
      parent::__construct( 'avante_faq', __('Avante FAQ Widget', 'avante-lite'), $widget_ops );
      
      //setup default widget data
  		$this->defaults = array(
    			'title'      => '',
    			'textarea'   => ''
  		);
    }

    /**
    * Outputs the HTML for this widget.
    *
    * @param array  An array of standard parameters for widgets in this theme
    * @param array  An array of settings for this widget instance
    * @return void Echoes it's output
    **/
    function widget( $args, $instance ) {
        wp_reset_postdata();
        extract( $args, EXTR_SKIP );
        // these are the widget options
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $textarea = apply_filters( 'widget_textarea', empty( $instance['textarea'] ) ? '' : $instance['textarea'], $instance );
        echo $before_widget;
        // Display the widget
        // Check if title is set
        if ( $title ) {
          echo $before_title . esc_html($title) . $after_title;
        }
        // Check if textarea is set
        if( $textarea ) {
          echo wpautop(wp_kses_post($textarea));
        }
        echo $after_widget;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

      // update logic goes here
      $instance = $old_instance;
      // Fields
      $instance['title'] = sanitize_text_field($new_instance['title']);
      if ( current_user_can('unfiltered_html') )
          $instance['textarea'] =  $new_instance['textarea'];
      else $instance['textarea'] = wp_kses_post($new_instance['textarea']);
        
      return $instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, $this->defaults );
  ?>
  <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('FAQ Question', 'avante-lite'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
  </p>
  <p>
      <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php esc_html_e('FAQ Answer', 'avante-lite'); ?></label>
      <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo wp_kses_post($instance['textarea']); ?></textarea>
      <i><?php esc_html_e('No limit on the amount of text and HTML is allowed.', 'avante-lite'); ?></i>
  </p>
<?php
    }
}
// End of Plugin Class
add_action( 'widgets_init', function() {
    register_widget( 'Avante_FAQ_Widget' );
} );
?>