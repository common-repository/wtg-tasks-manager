<?php         
/*
Plugin Name: WTG Tasks Manager Beta
Version: 0.0.40
Plugin URI: http://www.webtechglobal.co.uk/wtg-tasks-manager-wordpress
Description: Tasks management for multiple projects or use as an advanced To-Do list.
Author: WebTechGlobal
Author URI: http://www.webtechglobal.co.uk/
Last Updated: October 2015
Text Domain: wtgtasksmanager
Domain Path: /languages

GPL v3 

This program is free software downloaded from WordPress.org: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. This means
it can be provided for the sole purpose of being developed further
and we do not promise it is ready for any one persons specific needs.
See the GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.
*/           
  
// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'Direct script access is not allowed!' );

// exit early if WTG Tasks Manager doesn't have to be loaded
if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) ) // Login screen
    || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
    || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
    return;
}
              
// package variables
$wtgtasksmanager_currentversion = '0.0.39';# to be removed, version is now in the WTGTASKSMANAGER() class 
                                       
// define constants, feel free to add some of your own...                              
if(!defined( "WTGTASKSMANAGER_NAME") ){define( "WTGTASKSMANAGER_NAME", 'WTG Tasks Manager Beta' );} 
if(!defined( "WTGTASKSMANAGER__FILE__") ){define( "WTGTASKSMANAGER__FILE__", __FILE__);}
if(!defined( "WTGTASKSMANAGER_BASENAME") ){define( "WTGTASKSMANAGER_BASENAME",plugin_basename( WTGTASKSMANAGER__FILE__ ) );}
if(!defined( "WTGTASKSMANAGER_ABSPATH") ){define( "WTGTASKSMANAGER_ABSPATH", plugin_dir_path( __FILE__) );}//C:\AppServ\www\wordpress-testing\wtgplugintemplate\wp-content\plugins\wtgplugintemplate/  
if(!defined( "WTGTASKSMANAGER_PHPVERSIONMINIMUM") ){define( "WTGTASKSMANAGER_PHPVERSIONMINIMUM", '5.3.0' );}// The minimum php version that will allow the plugin to work                                
if(!defined( "WTGTASKSMANAGER_IMAGES_URL") ){define( "WTGTASKSMANAGER_IMAGES_URL",plugins_url( 'images/' , __FILE__ ) );}
if(!defined( "WTGTASKSMANAGER_PORTAL" ) ){define( "WTGTASKSMANAGER_PORTAL", 'http://www.webtechglobal.co.uk/wtg-tasks-manager-wordpress/' );}
if(!defined( "WTGTASKSMANAGER_FORUM" ) ){define( "WTGTASKSMANAGER_FORUM", 'http://forum.webtechglobal.co.uk/viewforum.php?f=40' );}
if(!defined( "WTGTASKSMANAGER_TWITTER" ) ){define( "WTGTASKSMANAGER_TWITTER", 'http://www.twitter.com/WebTechGlobal' );}
if(!defined( "WTGTASKSMANAGER_FACEBOOK" ) ){define( "WTGTASKSMANAGER_FACEBOOK", 'https://www.facebook.com/WebTechGlobal1/' );}
if(!defined( "WTGTASKSMANAGER_YOUTUBEPLAYLIST" ) ){define( "WTGTASKSMANAGER_YOUTUBEPLAYLIST", 'https://www.youtube.com/playlist?list=PLMYhfJnWwPWAh49jnSfNRwR_HSfnhCdF4' );}

// define WebTechGlobal constants applicable to all projects...
if(!defined( "WEBTECHGLOBAL_FULLINTEGRATION") ){define( "WEBTECHGLOBAL_FULLINTEGRATION", false );}// change to true to force tables and files to be shared among WTG plugins automatically
if(!defined( "WEBTECHGLOBAL_FORUM" ) ){define( "WEBTECHGLOBAL_FORUM", 'http://forum.webtechglobal.co.uk/' );}
if(!defined( "WEBTECHGLOBAL_TWITTER" ) ){define( "WEBTECHGLOBAL_TWITTER", 'http://www.twitter.com/WebTechGlobal/' );}
if(!defined( "WEBTECHGLOBAL_FACEBOOK" ) ){define( "WEBTECHGLOBAL_FACEBOOK", 'https://www.facebook.com/WebTechGlobal1/' );}
if(!defined( "WEBTECHGLOBAL_REGISTER" ) ){define( "WEBTECHGLOBAL_REGISTER", 'http://www.webtechglobal.co.uk/login/?action=register' );}
if(!defined( "WEBTECHGLOBAL_LOGIN" ) ){define( "WEBTECHGLOBAL_LOGIN", 'http://www.webtechglobal.co.uk/login/' );}
if(!defined( "WEBTECHGLOBAL_YOUTUBE" ) ){define( "WEBTECHGLOBAL_YOUTUBE", 'https://www.youtube.com/channel/UCVWMSHRCJ2hALZd1CYJrMXA' );}
      
// require main class...
// most common classes used in all other class
require_once( WTGTASKSMANAGER_ABSPATH . 'classes/class-wpdb.php' );
require_once( WTGTASKSMANAGER_ABSPATH . 'classes/class-log.php' );    
require_once( WTGTASKSMANAGER_ABSPATH . 'classes/class-configuration.php' );
require_once( WTGTASKSMANAGER_ABSPATH . 'classes/class-wtgtasksmanager.php' );

// call the Daddy (parent alright the parent lol) methods here or remove some lines as a quick configuration approach...
$WTGTASKSMANAGER = new WTGTASKSMANAGER();
$WTGTASKSMANAGER->custom_post_types();

// localization 
function wtgtasksmanager_textdomain() {
    load_plugin_textdomain( 'wtgtasksmanager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'wtgtasksmanager_textdomain' );                                                                                                     
?>