<?php 
/**
 * Hero Widget Format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Avante_Hero_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {
        $widget_ops = array( 
            'classname' => 'avante-hero-widget', 
            'description' => __('Add an image slider to the Hero section of the One-Page Template.', 'avante-lite') 
        );
        parent::__construct( 'avante_hero', __('Avante Hero Slider Widget', 'avante-lite'), $widget_ops );
        
        //setup default widget data
        $this->defaults = array(
            'title'        => '',
            'subtitle'     => '',
            'textarea'     => '',
            'button1_text' => '',
            'button1_url'  => '',
            'button2_text' => '',
            'button2_url'  => '',
            'image_url'    => '',
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
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $subtitle = apply_filters( 'widget_title', empty( $instance['subtitle'] ) ? '' : $instance['subtitle'], $instance, $this->id_base );
        $textarea = apply_filters( 'widget_textarea', empty( $instance['textarea'] ) ? '' : $instance['textarea'], $instance );
        $button1_text = apply_filters( 'widget_button1_text', empty( $instance['button1_text'] ) ? '' : $instance['button1_text'], $instance );
        $button1_url = apply_filters( 'widget_button1_url', empty( $instance['button1_url'] ) ? '' : $instance['button1_url'], $instance );
        $button2_text = apply_filters( 'widget_button2_text', empty( $instance['button2_text'] ) ? '' : $instance['button2_text'], $instance );
        $button2_url = apply_filters( 'widget_button2_url', empty( $instance['button2_url'] ) ? '' : $instance['button2_url'], $instance );
        $image_url = $instance['image_url'];
        echo $before_widget;

    /**
    * Display the widget
    **/
	?>

        <div class="item h-100" style="background-image: url(<?php echo esc_url( $image_url ); ?>);">

            <div class="container h-100">
        
                <div class="row h-100">
                    
                    <div class="col-12 my-auto">

                        <div class="title text-center mb-5">

                            <?php if ( $title ) : ?>
                                
                                <h1 class="title text-light"><?php echo esc_html( $title ); ?></h1>
                            
                            <?php endif; ?>

                            <?php if ( $subtitle ) : ?>
                            
                                <h2 class="sub-title mb-0 text-light"><?php echo esc_html( $subtitle ); ?></h2>
                            
                            <?php endif; ?>
                        
                        </div>

                        <?php if ( $textarea ) : ?>
                            
                        <div class="lead text-center font-weight-normal mb-5">
                            
                            <p class="mb-0 text-light"><?php echo wp_kses_post($textarea); ?></p>
                        
                        </div>

                        <?php endif; ?>

                        <div class="row">

                            <?php if ( $button1_text && $button1_url && $button2_text && $button2_url ) : ?>
                            
                            <div class="col-md-6 text-center text-md-right mb-4 mb-md-0">
                                
                                <a href="<?php echo esc_url( $button1_url ); ?>" class="btn btn-lg btn-pill border-0 btn-light"><?php echo esc_html( $button1_text ); ?></a>
                            
                            </div>
                            
                            <?php endif; ?>

                            <?php if ( ( $button1_text && $button1_url ) && ( !$button2_text || !$button2_url ) ) : ?>
                            
                            <div class="col-md-12 text-center">
                                
                                <a href="<?php echo esc_url( $button1_url ); ?>" class="btn btn-lg btn-pill border-0 btn-light"><?php echo esc_html( $button1_text ); ?></a>
                            
                            </div>
                            
                            <?php endif; ?>

                            <?php if ( $button1_text && $button1_url && $button2_text && $button2_url ) : ?>
                            
                            <div class="col-md-6 text-center text-md-left">
                            
                                <a href="<?php echo esc_url( $button2_url ); ?>" class="btn btn-lg btn-pill border-0 btn-primary"><?php echo esc_html( $button2_text ); ?></a>
                            
                            </div>
                            
                            <?php endif; ?>

                            <?php if ( ( !$button1_text || !$button1_url ) && ( $button2_text && $button2_url ) ) : ?>
                            
                            <div class="col-md-12 text-center">
                            
                                <a href="<?php echo esc_url( $button2_url ); ?>" class="btn btn-lg btn-pill border-0 btn-primary"><?php echo esc_html( $button2_text ); ?></a>
                            
                            </div>
                            
                            <?php endif; ?>

                        </div><!-- .row -->

                    </div><!-- .col-md-12 -->
                
                </div><!-- .row -->
            
            </div><!-- .container -->

        </div><!-- .item -->
    <?php
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
        $instance['subtitle'] = sanitize_text_field($new_instance['subtitle']);
        $instance['button1_text'] = sanitize_text_field($new_instance['button1_text']);
        $instance['button2_text'] = sanitize_text_field($new_instance['button2_text']);
        $instance['button1_url'] = sanitize_text_field($new_instance['button1_url']);
        $instance['button2_url'] = sanitize_text_field($new_instance['button2_url']);
        $instance['image_url'] = esc_url($new_instance['image_url']);
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
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Big Title', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php esc_html_e('Small Title', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($instance['subtitle']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php esc_html_e('Tagline', 'avante-lite'); ?></label>
        <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo wp_kses_post($instance['textarea']); ?></textarea>
        <i><?php esc_html_e('No limit on the amount of text and HTML is allowed.', 'avante-lite'); ?></i>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('button1_text'); ?>"><?php esc_html_e('Button 1 Text', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('button1_text'); ?>" name="<?php echo $this->get_field_name('button1_text'); ?>" type="text" value="<?php echo esc_attr($instance['button1_text']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('button1_url'); ?>"><?php esc_html_e('Button 1 URL', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('button1_url'); ?>" name="<?php echo $this->get_field_name('button1_url'); ?>" type="text" value="<?php echo esc_attr($instance['button1_url']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('button2_text'); ?>"><?php esc_html_e('Button 2 Text', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('button2_text'); ?>" name="<?php echo $this->get_field_name('button2_text'); ?>" type="text" value="<?php echo esc_attr($instance['button2_text']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('button2_url'); ?>"><?php esc_html_e('Button 2 URL', 'avante-lite'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('button2_url'); ?>" name="<?php echo $this->get_field_name('button2_url'); ?>" type="text" value="<?php echo esc_attr($instance['button2_url']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php esc_html_e('Background Image', 'avante-lite'); ?></label>
        <br /><i><?php esc_html_e('Recommended size of 1920 pixels by 1080 pixels (rectangle).', 'avante-lite'); ?></i>
        <input id="<?php echo $this->get_field_id('image_url'); ?>" type="text" class="image-url" name="<?php echo $this->get_field_name('image_url'); ?>" value="<?php echo esc_url($instance['image_url']); ?>" style="width: 100%;" />
        <input data-title="Image in Widget" data-btntext="Select it" class="button upload_image_button" type="button" value="<?php esc_html_e('Upload','avante-lite') ?>" />
        <input data-title="Image in Widget" data-btntext="Select it" class="button clear_image_button" type="button" value="<?php esc_html_e('Clear','avante-lite') ?>" />
    </p>
    <p class="img-prev">
          <img src="<?php echo esc_url($instance['image_url']); ?>" style="max-width: 100%;">
      </p>
<?php
    }
}
// End of Plugin Class
add_action( 'widgets_init', function() {
    register_widget( 'Avante_Hero_Widget' );
} );
?>
