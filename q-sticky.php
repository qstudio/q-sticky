<?php

/**
 * Plugin Name:     Q Sticky
 * Description:     Stick posts using a simple admin UI
 * Version:         3.0.0
 * Author:          Q Studio
 * Author URI:      http://qstudio.us
 * License:         GPL2
 * Class:           q_sticky
 * Text Domain:     q-sticky
 */

defined( 'ABSPATH' ) OR exit;

if ( ! class_exists( 'q_sticky' ) ) {
    
    // instatiate plugin via WP plugins_loaded - init is too late for CPT ##
    add_action( 'plugins_loaded', array ( 'q_sticky', 'get_instance' ), 5 );
    
    class q_sticky {
                
        // Refers to a single instance of this class. ##
        private static $instance = null;
                       
        // Plugin Settings
        public static $debug = false;
        public static $version = '3.0.0';
        public static $text_domain = 'q-sticky'; // for translation ##
        public static $post_types = [ 'post' ]; // default, filtered later ##
        
        // Function Settings ##
        const cache = false;
        
        /**
         * Creates or returns an instance of this class.
         *
         * @return  Foo     A single instance of this class.
         */
        public static function get_instance() 
        {

            if ( null == self::$instance ) {
                self::$instance = new self;
            }

            return self::$instance;

        }
        
        
        /**
         * Instatiate Class
         * 
         * @since       0.2
         * @return      void
         */
        private function __construct() 
        {

            // activation ##
            register_activation_hook( __FILE__, array ( $this, 'register_activation_hook' ) );

            // deactvation ##
            register_deactivation_hook( __FILE__, array ( $this, 'register_deactivation_hook' ) );

            // set text domain ##
            add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );
            
            // constants ##
            #$this->load_constants();

            // load libraries ##
            $this->load_libraries();

        }
        
        // the form for sites have to be 1-column-layout
        public function register_activation_hook() {

            #add_option( 'q_awards_configured' );

        }


        public function register_deactivation_hook() {

            #delete_option( 'q_awards_configured' );

        }


        
        /**
         * Load Text Domain for translations
         * 
         * @since       1.7.0
         * 
         */
        public function load_plugin_textdomain() 
        {
            
            // set text-domain ##
            $domain = self::$text_domain;
            
            // The "plugin_locale" filter is also used in load_plugin_textdomain()
            $locale = apply_filters('plugin_locale', get_locale(), $domain);

            // try from global WP location first ##
            load_textdomain( $domain, WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo' );
            
            // try from plugin last ##
            load_plugin_textdomain( $domain, FALSE, plugin_dir_path( __FILE__ ).'library/languages/' );
            
        }
        
        
        
        /**
         * Get Plugin URL
         * 
         * @since       0.1
         * @param       string      $path   Path to plugin directory
         * @return      string      Absoulte URL to plugin directory
         */
        public static function get_plugin_url( $path = '' ) 
        {

            return plugins_url( $path, __FILE__ );

        }
        
        
        /**
         * Get Plugin Path
         * 
         * @since       0.1
         * @param       string      $path   Path to plugin directory
         * @return      string      Absoulte URL to plugin directory
         */
        public static function get_plugin_path( $path = '' ) 
        {

            return plugin_dir_path( __FILE__ ).$path;

        }
        

        /**
        * Load Constants
        *
        * @since        2.0
        */
        public static function load_constants()
        {
            
            // const ##
            define( 'Q_STICKY_DIR', WP_PLUGIN_DIR.'/q-sticky/' );

        }


        /**
        * Load Libraries
        *
        * @since        2.0
        */
		private function load_libraries()
        {

            // methods ##
            require_once $this->get_plugin_path( 'library/core/helper.php' );
            require_once $this->get_plugin_path( 'library/core/core.php' );
            require_once $this->get_plugin_path( 'library/core/ajax.php' );

            // backend ##
            require_once $this->get_plugin_path( 'library/admin/admin.php' );
            require_once $this->get_plugin_path( 'library/admin/theme.php' );

        }


    }

}