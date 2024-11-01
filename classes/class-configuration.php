<?php  
/** 
 * Configuration for WTG Tasks Manager. Created September 2015 after
 * scheduling and automation system improved.
 * 
 * @package WTG Tasks Manager
 * @author Ryan Bayne   
 * @version 1.0.
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
                                               
class WTGTASKSMANAGER_Configuration {
  
    protected

        $plugin_shorcodes_array = array (
            array( 'displaytaskslist',    'display_tasks_list_shortcode' ),
        );
            
    /**
    * Plugins main actions.
    * 
    * @author Ryan R. Bayne
    * @package WebTechGlobal WordPress Plugins
    * @version 1.0
    */
    public function actions() {
        // add_action() controller
        // Format: array( event | function in this class(in an array if optional arguments are needed) | loading circumstances)
        // Other class requiring WordPress hooks start here also, with a method in this main class that calls one or more methods in one or many classes
        // create a method in this class for each hook required plugin wide
        return array( 
            array( 'admin_menu',                              'set_admin_globals',                              'all' ),        
            array( 'admin_menu',                              'admin_menu',                                     'all' ),
            array( 'admin_init',                              'process_admin_POST_GET',                         'all' ),
            array( 'admin_init',                              'add_adminpage_actions',                          'all' ), 
            array( 'init',                                    'event_check',                                    'all' ),
            array( 'eventcheckwpcron',                        'eventcheckwpcron',                               'all' ),
            array( 'event_check_servercron',                  'event_check_servercron',                         'all' ),
            array( 'wp_dashboard_setup',                      'add_dashboard_widgets',                          'all' ),
            array( 'wp_insert_post',                          'hook_insert_post',                               'all' ),
            array( 'admin_footer',                            'pluginmediabutton_popup',                        'pluginscreens' ),
            array( 'media_buttons_context',                   'pluginmediabutton_button',                       'pluginscreens' ),
            array( 'admin_enqueue_scripts',                   'plugin_admin_enqueue_scripts',                   'pluginscreens' ),
            array( 'init',                                    'plugin_admin_register_styles',                   'pluginscreens' ),
            array( 'admin_print_styles',                      'plugin_admin_print_styles',                      'pluginscreens' ),
            array( 'admin_notices',                           'admin_notices',                                  'admin_notices' ),
            array( 'wp_before_admin_bar_render',              array('admin_toolbars',999),                      'pluginscreens' ),
            array( 'init',                                    'plugin_shortcodes',                              'publicpages' ),
        );    
    }

    /**
    * Array of filters to be used during __construct of main class.
    * 
    * @author Ryan R. Bayne
    * @package WebTechGlobal WordPress Plugins
    * @version 1.0
    */
    public function filters() {
        return array(
            /*
                Examples - last value are the sections the filter apply to
                    array( 'plugin_row_meta',                     array( 'examplefunction1', 10, 2),         'all' ),
                    array( 'page_link',                             array( 'examplefunction2', 10, 2),             'downloads' ),
                    array( 'admin_footer_text',                     'examplefunction3',                         'monetization' ),
                    
            */
        );    
    }  
    
    /**
    * As of October 2015 this will be my approach to the initial settings. The
    * installation class will call this function.
    * 
    * @author Ryan R. Bayne
    * @package WebTechGlobal WordPress Plugins
    * @version 1.0
    */
    public function default_settings() {
        // install main admin settings option record
        $s = array();                                                                                             
        // encoding
        $s['standardsettings']['encoding']['type'] = 'utf8';
        // admin user interface settings start
        $s['standardsettings']['ui_advancedinfo'] = false;// hide advanced user interface information by default
        // other
        $s['standardsettings']['ecq'] = array();
        $s['standardsettings']['chmod'] = '0750';
        $s['standardsettings']['systematicpostupdating'] = 'enabled';
        // testing and development
        $s['standardsettings']['developementinsight'] = 'disabled';
        // global switches
        $s['standardsettings']['textspinrespinning'] = 'enabled';// disabled stops all text spin re-spinning and sticks to the last spin

        ##########################################################################################
        #                                                                                        #
        #                           SETTINGS WITH NO UI OPTION                                   #
        #              array key should be the method/function the setting is used in            #
        ##########################################################################################
        $s['create_localmedia_fromlocalimages']['destinationdirectory'] = 'wp-content/uploads/importedmedia/';
         
        ##########################################################################################
        #                                                                                        #
        #                            DATA IMPORT AND MANAGEMENT SETTINGS                         #
        #                                                                                        #
        ##########################################################################################
        $s['datasettings']['insertlimit'] = 100;

        ##########################################################################################
        #                                                                                        #
        #                                    WIDGET SETTINGS                                     #
        #                                                                                        #
        ##########################################################################################
        $s['widgetsettings']['dashboardwidgetsswitch'] = 'disabled';

        ##########################################################################################
        #                                                                                        #
        #                            CUSTOM POST TYPE SETTINGS                                   #
        #                                                                                        #
        ##########################################################################################
        $s['posttypes']['wtgflags']['status'] = 'disabled';
        $s['posttypes']['wtgtasks']['status'] = 'enabled';
        $s['posttypes']['wtgtasks']['public'] = false;
        $s['posttypes']['wtgtasks']['publicly_queryable'] = false;

        ##########################################################################################
        #                                                                                        #
        #                                    NOTICE SETTINGS                                     #
        #                                                                                        #
        ##########################################################################################
        $s['noticesettings']['wpcorestyle'] = 'enabled';

        ##########################################################################################
        #                                                                                        #
        #                           YOUTUBE RELATED SETTINGS                                     #
        #                                                                                        #
        ##########################################################################################
        $s['youtubesettings']['defaultcolor'] = '&color1=0x2b405b&color2=0x6b8ab6';
        $s['youtubesettings']['defaultborder'] = 'enable';
        $s['youtubesettings']['defaultautoplay'] = 'enable';
        $s['youtubesettings']['defaultfullscreen'] = 'enable';
        $s['youtubesettings']['defaultscriptaccess'] = 'always';

        ##########################################################################################
        #                                                                                        #
        #                                  LOG SETTINGS                                          #
        #                                                                                        #
        ##########################################################################################
        $s['logsettings']['uselog'] = 1;
        $s['logsettings']['loglimit'] = 1000;
        $s['logsettings']['logscreen']['displayedcolumns']['outcome'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['timestamp'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['line'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['function'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['page'] = true; 
        $s['logsettings']['logscreen']['displayedcolumns']['panelname'] = true;   
        $s['logsettings']['logscreen']['displayedcolumns']['userid'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['type'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['category'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['action'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['priority'] = true;
        $s['logsettings']['logscreen']['displayedcolumns']['comment'] = true;
        
        return $s;        
    }
    
    /**
    * Error display and debugging 
    * 
    * When request will display maximum php errors including WordPress errors 
    * 
    * @author Ryan R. Bayne
    * @package WebTechGlobal WordPress Plugins
    * @version 0.1
    * 
    * @todo is this function in use? It may be in another file.
    */
    public function debugmode() {

        $debug_status = get_option( 'webtechglobal_displayerrors' );
        if( !$debug_status ){ return false; }
        
        // times when this error display is normally not  required
        if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) )
                || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
                || ( defined( 'DOING_CRON' ) && DOING_CRON )
                || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
                    return false;
        }    
            
        global $wpdb;
        ini_set( 'display_errors',1);
        error_reporting(E_ALL);      
        if(!defined( "WP_DEBUG_DISPLAY") ){define( "WP_DEBUG_DISPLAY", true);}
        if(!defined( "WP_DEBUG_LOG") ){define( "WP_DEBUG_LOG", true);}
        //add_action( 'all', create_function( '', 'var_dump( current_filter() );' ) );
        //define( 'SAVEQUERIES', true );
        //define( 'SCRIPT_DEBUG', true );
        $wpdb->show_errors();
        $wpdb->print_error();
        
        // constant required for package - everything before now is global to all
        // of WordPress and the error display switch is global to all WTG plugins
        if(!defined( "WEBTECHGLOBAL_ERRORDISPLAY") ){define( "WEBTECHGLOBAL_ERRORDISPLAY", true );}
    }  
    
    /**
    * Create a new instance of the $class, which is stored in $file in the $folder subfolder
    * of the plugin's directory.
    * 
    * One bad thing about using this is suggestive code does not work on the object that is returned
    * making development a little more difficult. This behaviour is experienced in phpEd 
    *
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    *
    * @param string $class Name of the class
    * @param string $file Name of the PHP file with the class
    * @param string $folder Name of the folder with $class's $file
    * @param mixed $params (optional) Parameters that are passed to the constructor of $class
    * @return object Initialized instance of the class
    */
    public static function load_class( $class, $file, $folder, $params = null ) {
        /**
         * Filter name of the class that shall be loaded.
         *
         * @since 0.0.1
         *
         * @param string $class Name of the class that shall be loaded.
         */        
        $class = apply_filters( 'wtgtasksmanager_load_class_name', $class );
        if ( ! class_exists( $class ) ) {   
            self::load_file( $file, $folder );
        }
        
        // we can avoid creating a new object, we can use "new" after the load_class() line
        // that way functions in the lass are available in code suggestion
        if( is_array( $params ) && in_array( 'noreturn', $params ) ){
            return true;   
        }
        
        $the_class = new $class( $params );
        return $the_class;
    }
    
    /**
     * Load a file with require_once(), after running it through a filter
     *
     * @since 0.0.1
     *
     * @param string $file Name of the PHP file with the class
     * @param string $folder Name of the folder with $class's $file
     */
    public static function load_file( $file, $folder ) {   
        $full_path = WTGTASKSMANAGER_ABSPATH . $folder . '/' . $file;
        //Filter the full path of a file that shall be loaded
        $full_path = apply_filters( 'wtgtasksmanager_load_file_full_path', $full_path, $file, $folder );
        if ( $full_path ) {   
            require_once $full_path;
        }
    }           
}
?>
