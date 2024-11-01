<?php
/** 
 * WebTechGlobal Log entry and display classes for WordPress.
 * 
 * To be used with WTG Tasks Manager. The wp_webtechglobal_log table is shared between
 * all WebTechGlobal plugins. The package is stored in the table to separate log entries.
 * 
 * @package WTG Tasks Manager
 * @subpackage WebTechGlobal Log for WordPress
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
                                                                                 
class WTGTASKSMANAGER_Log { 
    
    public function __construct() {

    }

    /**
    * get all actions logged into database
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @version 1.0 
    */
    public function getactions() {
        global $wpdb;    
        return $wpdb->get_results( 'SELECT DISTINCT action FROM '.$wpdb->prefix.'webtechglobal_log',ARRAY_A );    
    }        
}
?>