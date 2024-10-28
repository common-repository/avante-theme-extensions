<?php 
/**
 * WordPress Widget Format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Avante_Showcase_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {

        // This is where we add the style and script
        add_action( 'load-widgets.php', array(&$this, 'avante_lite_widgets_color_picker') );

        $widget_ops = array( 
            'classname' => 'avante-showcase-widget', 
            'description' => __('Add a showcase detail to the Showcase section of the One-Page Template.', 'avante-lite') 
        );
        parent::__construct( 'avante_showcase', __('Avante Showcase Widget', 'avante-lite'), $widget_ops );
        //setup default widget data
        $this->defaults = array(
          'title'          => '',
          'style'          => '',
          'icon_name'      => '',
          'color'          => '',
          'icon_image_url' => '',
          'textarea'       => '',
        );
    }

    /**
     * Enqueue the color picker styles and script.
     **/
    function avante_lite_widgets_color_picker() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'underscore' );
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
        $style = $instance['style'];
        $icon_name = $instance['icon_name'];
        $color = $instance['color'];
        $icon_image_url = $instance['icon_image_url'];
        $icon_textarea = apply_filters( 'widget_textarea', empty( $instance['textarea'] ) ? '' : $instance['textarea'], $instance );
        echo $before_widget;
        // Display the widget
        // Check if text is set
        if( $icon_name ) {
            echo '<i class="'.esc_attr($style).' fa-'.esc_attr($icon_name).' fa-2x icon text-green" style="color:'.esc_attr($color).' !important;"></i>';
        }
        if( !$icon_name && $icon_image_url) {
            echo '<span class="icon-wrapper"><img  src="'.esc_url($icon_image_url).'" class="icon"></span>';
        }
        // Check if title is set
        if ( $title ) {
            echo $before_title . esc_html($title) . $after_title;
        }
        // Check if textarea is set
        if( $icon_textarea ) {
            echo wpautop(wp_kses_post($icon_textarea));
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
        $instance['style'] = sanitize_text_field($new_instance['style']);
        $instance['icon_name'] = sanitize_text_field($new_instance['icon_name']);
        $instance['color'] = esc_attr($new_instance['color']);
        $instance['icon_image_url'] = esc_url($new_instance['icon_image_url']);

        if ( current_user_can('unfiltered_html') ) {
            $instance['textarea'] =  $new_instance['textarea'];
        } else {
            $instance['textarea'] = wp_kses_post($new_instance['textarea']);
        }

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
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Showcase Title', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('style'); ?>"><?php esc_html_e('Showcase Icon Style', 'avante-lite'); ?></label>
        <select class='widefat' id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
            <option value="fas" <?php selected( $instance['style'], 'fas' ); ?>>Solid</option>
            <option value="far" <?php selected( $instance['style'], 'far' ); ?>>Regular</option>
            <option value="fab" <?php selected( $instance['style'], 'fab' ); ?>>Brands</option>
        </select>
        <i><?php esc_html_e('Select which style to use for this icon. Each icon page displays the icon name and style directly beneath it.', 'avante-lite'); ?> <a href="<?php echo esc_url('https://fontawesome.com/icons/apple?style=brands'); ?>" target="_blank"><?php esc_html_e('Here is an example', 'avante-lite'); ?></a>.</i>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('icon_name'); ?>"><?php esc_html_e('Showcase Icon Name', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('icon_name'); ?>" name="<?php echo $this->get_field_name('icon_name'); ?>" type="text" value="<?php echo esc_attr($instance['icon_name']); ?>" />
        <i><?php esc_html_e('Enter an icon name from the', 'avante-lite'); ?> <a href="<?php echo esc_url('https://fontawesome.com/icons?d=gallery&m=free'); ?>" target="_blank"><?php esc_html_e('Fontawesome Icons List', 'avante-lite'); ?></a> <?php esc_html_e('and choose from 1500+ icons. Example: Enter', 'avante-lite'); ?> <strong><?php esc_html_e('smile', 'avante-lite'); ?></strong> <?php esc_html_e('to display a smiley face icon.', 'avante-lite'); ?></i>
    </p>
    <p>
        <script>
            ( function( $ ){
                function initColorPicker( widget ) {
                    widget.find( '.color-picker' ).wpColorPicker( {
                        change: _.throttle( function() {
                            $(this).trigger( 'change' );
                        }, 3000 )
                    });
                }

                function onFormUpdate( event, widget ) {
                    initColorPicker( widget );
                }

                $( document ).on( 'widget-added widget-updated', onFormUpdate );

                $( document ).ready( function() {
                    $( '#widgets-right .widget:has(.color-picker)' ).each( function () {
                        initColorPicker( $( this ) );
                    } );
                } );
            }( jQuery ) );
        </script>
        <label for="<?php echo $this->get_field_id( 'color' ); ?>" style="width:100%;display:inline-block;"><?php esc_html_e('Showcase Icon Color', 'avante-lite'); ?></label>
        <input class="color-picker" type="text" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" value="<?php echo esc_attr( $instance['color'] ); ?>" data-default-color="#12dabd" />                       
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('icon_image_url'); ?>"><?php esc_html_e('Showcase Icon Image', 'avante-lite'); ?></label>
        <br /><i><?php esc_html_e('Instead of using Fontawesome to display an icon you can also upload an image.', 'avante-lite'); ?></i>
        <input id="<?php echo $this->get_field_id('icon_image_url'); ?>" type="text" class="image-url" name="<?php echo $this->get_field_name('icon_image_url'); ?>" value="<?php echo esc_url($instance['icon_image_url']); ?>" style="width: 100%;" />
        <input data-title="Image in Widget" data-btntext="Select it" class="button upload_image_button" type="button" value="<?php esc_html_e('Upload','avante-lite') ?>" />
        <input data-title="Image in Widget" data-btntext="Select it" class="button clear_image_button" type="button" value="<?php esc_html_e('Clear','avante-lite') ?>" />
    </p>
    <p class="img-prev">
        <img src="<?php echo esc_url($instance['icon_image_url']); ?>" style="max-width: 100%;">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php esc_html_e('Showcase Description', 'avante-lite'); ?></label>
        <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo wp_kses_post($instance['textarea']); ?></textarea>
        <i><?php esc_html_e('No limit on the amount of text and HTML is allowed.', 'avante-lite'); ?></i>
    </p>
<?php
    }
}
// End of Widget Class
add_action( 'widgets_init', function() {
    register_widget( 'Avante_Showcase_Widget' );
} );
?>