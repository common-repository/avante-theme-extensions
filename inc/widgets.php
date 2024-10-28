<?php
	require AVANTE_EXTENSIONS_PATH . 'widgets/benefit_widget.php';
	require AVANTE_EXTENSIONS_PATH . 'widgets/stats_widget.php';
	require AVANTE_EXTENSIONS_PATH . 'widgets/testimonial_widget.php';
	require AVANTE_EXTENSIONS_PATH . 'widgets/pricing_widget.php';
	/*
	*	Check for Pro Version
	*/
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Avante Pro' == $theme->name || 'Avante Pro' == $theme->parent_theme ) {
		require AVANTE_EXTENSIONS_PATH . 'widgets/hero_widget.php';
	    require AVANTE_EXTENSIONS_PATH . 'widgets/counter_widget.php';
	    require AVANTE_EXTENSIONS_PATH . 'widgets/team_widget.php';
		require AVANTE_EXTENSIONS_PATH . 'widgets/showcase_widget.php';
		require AVANTE_EXTENSIONS_PATH . 'widgets/showcase_hero_widget.php';
		require AVANTE_EXTENSIONS_PATH . 'widgets/faq_widget.php';
		require AVANTE_EXTENSIONS_PATH . 'widgets/support_widget.php';
		require AVANTE_EXTENSIONS_PATH . 'widgets/skills_widget.php';
	}
	add_action( 'admin_enqueue_scripts', 'avante_extensions_upload_script' );
	add_action( 'admin_enqueue_scripts', 'avante_extensions_color_picker' );
	add_action( 'wp_head', 'avante_extensions_image_styles' );
	/*
	* Script for media uploader
	*/
	function avante_extensions_upload_script($hook){
	    if ( 'widgets.php' != $hook ) {
	        return;
	    }
	    wp_enqueue_media();
	    wp_enqueue_script('avante-wcp-uploader', AVANTE_EXTENSIONS_URL . 'js/admin.js', array('jquery') );
	    wp_enqueue_script('jquery-ui-datepicker');
	    wp_enqueue_script('jquery-ui-core');
	}
	/*
	* Styles for image previews
	*/
	function avante_extensions_image_styles() {
		wp_register_style('avante-wcp-image-styles', AVANTE_EXTENSIONS_URL . 'css/widgets.css' );
	}
	/**
     * Script and styles for color picker.
    */
    function avante_extensions_color_picker() {    
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );    
    }
?>