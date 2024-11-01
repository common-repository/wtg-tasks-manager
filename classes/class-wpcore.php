<?php
/** 
 * WordPress close to core class and functions i.e. WP functions slightly adapted or
 * functions which take global WP values and change them before using them
 * 
 * @package Opus
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * WordPress close to core class and functions i.e. WP functions slightly adapted or
 * functions which take global WP values and change them before using them
* 
* @author Ryan R. Bayne
* @package Opus
* @since 0.0.1
* @version 1.0 
*/
class WTGTASKSMANAGER_WPCore {  
    /**
    * Not Complete, I think this function was intended to merge custom capabilities into a single array,
    * but not 100% sure.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function capabilities() {
        global $wp_roles; 
        $capabilities_array = array();
        foreach( $wp_roles->roles as $role => $role_array ) { 
            $capabilities_array = array_merge( $capabilities_array, $role_array['capabilities'] );    
        }
        return $capabilities_array;
    }    
}
?>