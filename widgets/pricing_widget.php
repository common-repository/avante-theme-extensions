<?php 
/**
 * WordPress Widget Format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Avante_Pricing_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {
        // This is where we add the style and script
        add_action( 'load-widgets.php', array(&$this, 'avante_lite_widgets_color_picker') );

        $widget_ops = array( 
            'classname' => 'avante-pricing-widget', 
            'description' => __('Add a pricing table to the Plans & Pricing section of the One-Page Template.', 'avante-lite') 
        );
        parent::__construct( 'avante_pricing', __('Avante Pricing Table Widget', 'avante-lite'), $widget_ops );

        //setup default widget data
        $this->defaults = array(
          'featured'     => false,
          'icon_style'   => 'solid',
          'icon_color'   => '#12e1b6',
          'button_style' => 'solid',
          'button_color' => '#12e1b6',
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


    /* Convert hexdec color string to rgb(a) string */

    function hex2rgba($color, $opacity = false) {

      $default = 'rgb(0,0,0)';

      //Return default if no color provided
      if(empty($color))
              return $default; 

      //Sanitize $color if "#" is provided 
      if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
      }

      //Check if color has 6 or 3 characters and get values
      if (strlen($color) == 6) {
              $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
      } elseif ( strlen( $color ) == 3 ) {
              $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
      } else {
              return $default;
      }

      //Convert hexadec to rgb
      $rgb =  array_map('hexdec', $hex);

      //Check if opacity is set(rgba or rgb)
      if($opacity){
        if(abs($opacity) > 1)
          $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
      } else {
        $output = 'rgb('.implode(",",$rgb).')';
      }

      //Return rgb(a) color string
      return $output;
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );

        $title = apply_filters( 'widget_title', ! empty( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
        $featured = ! empty( $instance['featured'] ) ? 'featured-pricing ' : '';
        $bg_color = ! empty( $instance['bg_color'] ) ? 'background-color: ' . $instance['bg_color'] . ';' : '';
        $price = $instance['price'];
        $price_sub_text = $instance['price_sub_text'];
        $price_content = $instance['price_content'];
        $icon_style = $instance['icon_style'];
        $fa_icon_name = $instance['fa_icon_name'];
        $icon_color = $instance['icon_color'];
        $icon_url = $instance['icon_url'];
        $button_text = $instance['button_text'];
        $button_url = $instance['button_url'];
        $button_style = $instance['button_style'];
        $button_color = $instance['button_color'];

        // update widget class
        $before_widget = str_replace('class="', "class=\"$featured", $before_widget);

        echo $before_widget;

        // Display the widget
        echo '<div class="pricing-table text-center" style="'.esc_attr($bg_color).'">';

        echo '<div class="table-wrapper">';

        if ( $icon_url ) {
            echo '<span class="icon-wrapper">';
              echo '<img  src="'.esc_url($icon_url).'" class="icon">';
            echo '</span>';
        } elseif ( $fa_icon_name ) {
            echo '<span class="icon-wrapper">';
              echo '<i class="'.esc_attr($icon_style).' fa-'.esc_attr($fa_icon_name).' fa-3x icon" style="color:'.esc_attr($icon_color).' !important;"></i>';
            echo '</span>';
        }

        if ( $title ) {
          echo $before_title . esc_html($title) . $after_title;
        }

        echo '<p class="price">' . esc_html($price) . '</p>';
        echo '<p class="sub-text">' . esc_html($price_sub_text) . '</p>';
        echo '<p class="text-content">' . esc_html($price_content) . '</p>';
        echo '</div>';

        if ( ! $button_color ) {
          $style = '';
        } elseif ( 'solid' === $button_style ) {
          $style = '
            border-color:' . esc_attr($button_color) . ';
            background-color:' . esc_attr($button_color) . ';
            color: white;
          ';
          $style_hover = '
            background-color: white;
            color: ' . esc_attr($button_color) . ';
          ';
        } elseif ( 'outline' === $button_style ) {
          $style = '
            border-color:' . esc_attr($button_color) . ';
            background-color: white;
            color:' . esc_attr($button_color) . ';
          ';
          $style_hover = '
            background-color: ' . esc_attr($button_color) . ';
            color: white;
          ';
        }

        echo '
          <style>
            #' . $this->id . ' .pricing-table .btn-pricing {
              ' . $style . '
            }

            #' . $this->id . ' .pricing-table .btn-pricing:hover,
            #' . $this->id . ' .pricing-table .btn-pricing:focus {
              ' . $style_hover . '
              -webkit-box-shadow: 0 16px 27px ' . $this->hex2rgba( esc_attr($button_color) , .23 ) . ';
                      box-shadow: 0 16px 27px ' . $this->hex2rgba( esc_attr($button_color) , .23 ) . ';
            }

            #' . $this->id . ' .pricing-table .btn-pricing:active {
              -webkit-box-shadow: 0 8px 13px ' . $this->hex2rgba( esc_attr($button_color) , .33 ) . ';
                      box-shadow: 0 8px 13px ' . $this->hex2rgba( esc_attr($button_color) , .33 ) . ';
            }
          </style>
        ';

        echo '<p class="text-center">';

        if ( $button_text && $button_url ) {
            echo '<a href="' . esc_url($button_url) . '" class="btn btn-lg btn-pill btn-pricing">' . esc_html($button_text) . '</a>';
        }

        echo '</p>';

        echo '</div>';

        echo $after_widget;
    }

    /*
     * Sanitize boolean value
     */
    function sanitize_bool( $val ) {
      if ( ! empty( $val ) ) {
        return true;
      } else {
        return false;
      }
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
      $instance['featured'] = $this->sanitize_bool( $new_instance['featured'] );
      $instance['bg_color'] = esc_attr($new_instance['bg_color']);
      $instance['price'] = sanitize_text_field($new_instance['price']);
      $instance['price_sub_text'] = wp_kses_post($new_instance['price_sub_text']);
      $instance['price_content'] = wp_kses_post($new_instance['price_content']);
      $instance['fa_icon_name'] = sanitize_text_field($new_instance['fa_icon_name']);
      $instance['icon_style'] = sanitize_text_field($new_instance['icon_style']);
      $instance['icon_color'] = esc_attr($new_instance['icon_color']);
      $instance['icon_url'] = esc_url($new_instance['icon_url']);
      $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
      $instance['button_url'] = esc_url($new_instance['button_url']);
      $instance['button_style'] = sanitize_text_field($new_instance['button_style']);
      $instance['button_color'] = esc_attr($new_instance['button_color']);
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
  <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'avante-lite'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
  </p>

  <p>
      <input id="<?php echo $this->get_field_id( 'featured' ); ?>" name="<?php echo $this->get_field_name( 'featured' ); ?>" type="checkbox"<?php checked( ! empty( $instance['featured'] ) ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('featured'); ?>"><?php esc_html_e('Featured Widget', 'avante-lite'); ?></label>
  </p>

  <p>
      <label for="<?php echo $this->get_field_id( 'bg_color' ); ?>" style="width:100%;display:inline-block;"><?php esc_html_e('Background Color', 'avante-lite'); ?></label>
      <input class="color-picker" type="text" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" value="<?php echo esc_attr( $instance['bg_color'] ); ?>" data-default-color="#ffffff" />
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('price'); ?>"><?php esc_html_e('Price', 'avante-lite'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('price'); ?>" name="<?php echo $this->get_field_name('price'); ?>" type="text" value="<?php echo esc_attr($instance['price']); ?>" />
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('price_sub_text'); ?>"><?php esc_html_e('Price Sub Text', 'avante-lite'); ?></label>
      <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('price_sub_text'); ?>" name="<?php echo $this->get_field_name('price_sub_text'); ?>"><?php echo wp_kses_post($instance['price_sub_text']); ?></textarea>
      <i><?php esc_html_e('No limit on the amount of text and HTML is allowed.', 'avante-lite'); ?></i>
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('price_content'); ?>"><?php esc_html_e('Price Content', 'avante-lite'); ?></label>
      <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('price_content'); ?>" name="<?php echo $this->get_field_name('price_content'); ?>"><?php echo wp_kses_post($instance['price_content']); ?></textarea>
      <i><?php esc_html_e('No limit on the amount of text and HTML is allowed.', 'avante-lite'); ?></i>
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('fa_icon_name'); ?>"><?php esc_html_e('Icon Name', 'avante-lite'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('fa_icon_name'); ?>" name="<?php echo $this->get_field_name('fa_icon_name'); ?>" type="text" value="<?php echo esc_attr($instance['fa_icon_name']); ?>" />
      <i><?php esc_html_e('Enter an icon name from the', 'avante-lite'); ?> <a href="<?php echo esc_url('https://fontawesome.com/icons?d=gallery&m=free'); ?>" target="_blank"><?php esc_html_e('Fontawesome Icons List', 'avante-lite'); ?></a> <?php esc_html_e('and choose from 1500+ icons. Example: Enter', 'avante-lite'); ?> <strong><?php esc_html_e('smile', 'avante-lite'); ?></strong> <?php esc_html_e('to display a smiley face icon.', 'avante-lite'); ?></i>
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('icon_style'); ?>"><?php esc_html_e('Icon Style', 'avante-lite'); ?></label>
      <select class='widefat' id="<?php echo $this->get_field_id('icon_style'); ?>" name="<?php echo $this->get_field_name('icon_style'); ?>">
          <option value="fas" <?php selected( $instance['icon_style'], 'fas' ); ?>>Solid</option>
          <option value="far" <?php selected( $instance['icon_style'], 'far' ); ?>>Regular</option>
          <option value="fab" <?php selected( $instance['icon_style'], 'fab' ); ?>>Brands</option>
      </select>
      <i><?php esc_html_e('Select which style to use for this icon. Each icon page displays the icon name and style directly beneath it.', 'avante-lite'); ?> <a href="<?php echo esc_url('https://fontawesome.com/icons/apple?style=brands'); ?>" target="_blank"><?php esc_html_e('Here is an example', 'avante-lite'); ?></a>.</i>
  </p>

  <p>
      <label for="<?php echo $this->get_field_id( 'icon_color' ); ?>" style="width:100%;display:inline-block;"><?php esc_html_e('Icon Color', 'avante-lite'); ?></label>
      <input class="color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" value="<?php echo esc_attr( $instance['icon_color'] ); ?>" data-default-color="#12dabd" />                       
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('icon_url'); ?>"><?php esc_html_e('Icon Image', 'avante-lite'); ?></label>
      <br /><i><?php esc_html_e('Instead of using Fontawesome to display an icon you can also upload an image.', 'avante-lite'); ?></i>
      <input id="<?php echo $this->get_field_id('icon_url'); ?>" type="text" class="image-url" name="<?php echo $this->get_field_name('icon_url'); ?>" value="<?php echo esc_url($instance['icon_url']); ?>" style="width: 100%;" />
      <input data-title="Image in Widget" data-btntext="Select it" class="button upload_image_button" type="button" value="<?php esc_html_e('Upload','avante-lite') ?>" />
      <input data-title="Image in Widget" data-btntext="Select it" class="button clear_image_button" type="button" value="<?php esc_html_e('Clear','avante-lite') ?>" />
  </p>

  <p class="img-prev">
      <img src="<?php echo esc_url($instance['icon_url']); ?>" style="max-width: 100%;">
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php esc_html_e('Button Text', 'avante-lite'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($instance['button_text']); ?>" />
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php esc_html_e('Button URL', 'avante-lite'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="text" value="<?php echo esc_url($instance['button_url']); ?>" />
  </p>

  <p>
      <label for="<?php echo $this->get_field_id('button_style'); ?>"><?php esc_html_e('Button Style', 'avante-lite'); ?></label>
      <select class='widefat' id="<?php echo $this->get_field_id('button_style'); ?>" name="<?php echo $this->get_field_name('button_style'); ?>">
          <option value="solid" <?php selected( $instance['button_style'], 'solid' ); ?>>Solid</option>
          <option value="outline" <?php selected( $instance['button_style'], 'outline' ); ?>>Outline</option>
      </select>
      <i><?php esc_html_e('Select which style to use for this button.', 'avante-lite'); ?></i>
  </p>

  <p>
    <label for="<?php echo $this->get_field_id( 'button_color' ); ?>" style="width:100%;display:inline-block;"><?php esc_html_e('Button Color', 'avante-lite'); ?></label>
    <input class="color-picker" type="text" id="<?php echo $this->get_field_id( 'button_color' ); ?>" name="<?php echo $this->get_field_name( 'button_color' ); ?>" value="<?php echo esc_attr( $instance['button_color'] ); ?>" data-default-color="#12dabd" />                       
  </p>

<?php
    }
}

// End of Plugin Class
add_action( 'widgets_init', function() {
    register_widget( 'Avante_Pricing_Widget' );
} );

?>