<?php
/** 
* Class for handling $_POST and $_GET requests
* 
* The class is called in the process_admin_POST_GET() method found in the WTGTASKSMANAGER class. 
* The process_admin_POST_GET() method is hooked at admin_init. It means requests are handled in the admin
* head, globals can be updated and pages will show the most recent data. Nonce security is performed
* within process_admin_POST_GET() then the require method for processing the request is used.
* 
* Methods in this class MUST be named within the form or link itself, basically a unique identifier for the form.
* i.e. the Section Switches settings have a form name of "sectionswitches" and so the method in this class used to
* save submission of the "sectionswitches" form is named "sectionswitches".
* 
* process_admin_POST_GET() uses eval() to call class + method 
* 
* @package WTG Tasks Manager
* @author Ryan Bayne   
* @since 0.0.1
*/

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
* Class processes form submissions, the class is only loaded once nonce and other security checked
* 
* @author Ryan R. Bayne
* @package WTG Tasks Manager
* @since 0.0.1
* @version 1.0.2
*/
class WTGTASKSMANAGER_Requests { 

    // array of permitted "action" or early validation
    var $permitted_GET = array( 'canceltasks' );
     
    public function __construct() {
        global $tasksmanager_settings;
    
        // create class objects
        $this->WTGTASKSMANAGER = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER', 'class-wtgtasksmanager.php', 'classes' ); # plugin specific functions
        $this->UI = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_UI', 'class-ui.php', 'classes' ); # interface, mainly notices
        $this->DB = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_DB', 'class-wpdb.php', 'classes' ); # database interaction
        $this->PHP = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_PHP', 'class-phplibrary.php', 'classes' ); # php library by Ryan R. Bayne
        $this->Files = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_Files', 'class-files.php', 'classes' );
        $this->Forms = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_Formbuilder', 'class-forms.php', 'classes' );
        $this->WPCore = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_WPCore', 'class-wpcore.php', 'classes' );
   }
    
    /**
    * Applies WebTechGlobals own security for $_POST and $_GET requests. It involves
    * a range of validation, including ensuring HTML source edit was not performed before
    * users submission.
    * 
    * This function is called by process_admin_POST_GET() which is hooked by admin_init.
    * None security is done in that function before this class-request.php file is loaded.
    * 
    * @parameter $method is post or get or ajax
    * @parameter $function the method for completing the request, to be found in this class
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.3
    */
    public function process_admin_request( $method, $function ) {  
      
        // arriving here means check_admin_referer() security is positive       
        global $wtgtasksmanager_debug_mode, $cont;

        $this->PHP->var_dump( $_POST, '<h1>$_POST</h1>' );           
        $this->PHP->var_dump( $_GET, '<h1>$_GET</h1>' );    
        
        // $_POST security
        if( $method == 'post' || $method == 'POST' || $method == '$_POST' ) {                      
            // check_admin_referer() wp_die()'s if security fails so if we arrive here WordPress security has been passed
            // now we validate individual values against their pre-registered validation method
            // some generic notices are displayed - this system makes development faster
            $post_result = true;
            $post_result = $this->Forms->apply_form_security();// ensures $_POST['wtgtasksmanager_form_formid'] is set, so we can use it after this line
            
            // apply my own level of security per individual input
            if( $post_result ){ $post_result = $this->Forms->apply_input_security(); }// detect hacking of individual inputs i.e. disabled inputs being enabled 
            
            // validate users values
            if( $post_result ){ $post_result = $this->Forms->apply_input_validation( $_POST['wtgtasksmanager_form_formid'] ); }// values (string,numeric,mixed) validation

            // cleanup to reduce registered data
            $this->Forms->deregister_form( $_POST['wtgtasksmanager_form_formid'] );
                    
            // if $overall_result includes a single failure then there is no need to call the final function
            if( $post_result === false ) {        
                return false;
            }
        }
        
        // handle a situation where the submitted form requests a function that does not exist
        if( !method_exists( $this, $function ) ){
            wp_die( sprintf( __( "The method for processing your request was not found. This can usually be resolved quickly. Please report method %s does not exist. <a href='https://www.youtube.com/watch?v=vAImGQJdO_k' target='_blank'>Watch a video</a> explaining this problem.", 'wtgtasksmanager' ), 
            $function ) ); 
            return false;// should not be required with wp_die() but it helps to add clarity when browsing code and is a precaution.   
        }
        
        // all security passed - call the processing function
        if( isset( $function) && is_string( $function ) ) {
            eval( 'self::' . $function .'();' );
        }
    }  

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */    
    public function request_success( $form_title, $more_info = '' ){  
        $this->UI->create_notice( "Your submission for $form_title was successful. " . $more_info, 'success', 'Small', "$form_title Updated");          
    } 

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */    
    public function request_failed( $form_title, $reason = '' ){
        $this->UI->n_depreciated( $form_title . ' Unchanged', "Your settings for $form_title were not changed. " . $reason, 'error', 'Small' );    
    }

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */    
    public function logsettings() {
        global $tasksmanager_settings;
        $tasksmanager_settings['globalsettings']['uselog'] = $_POST['wtgtasksmanager_radiogroup_logstatus'];
        $tasksmanager_settings['globalsettings']['loglimit'] = $_POST['wtgtasksmanager_loglimit'];
                                                   
        ##################################################
        #           LOG SEARCH CRITERIA                  #
        ##################################################
        
        // first unset all criteria
        if( isset( $tasksmanager_settings['logsettings']['logscreen'] ) ){
            unset( $tasksmanager_settings['logsettings']['logscreen'] );
        }
                                                           
        // if a column is set in the array, it indicates that it is to be displayed, we unset those not to be set, we dont set them to false
        if( isset( $_POST['wtgtasksmanager_logfields'] ) ){
            foreach( $_POST['wtgtasksmanager_logfields'] as $column){
                $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns'][$column] = true;                   
            }
        }
                                                                                 
        // outcome criteria
        if( isset( $_POST['wtgtasksmanager_log_outcome'] ) ){    
            foreach( $_POST['wtgtasksmanager_log_outcome'] as $outcomecriteria){
                $tasksmanager_settings['logsettings']['logscreen']['outcomecriteria'][$outcomecriteria] = true;                   
            }            
        } 
        
        // type criteria
        if( isset( $_POST['wtgtasksmanager_log_type'] ) ){
            foreach( $_POST['wtgtasksmanager_log_type'] as $typecriteria){
                $tasksmanager_settings['logsettings']['logscreen']['typecriteria'][$typecriteria] = true;                   
            }            
        }         

        // category criteria
        if( isset( $_POST['wtgtasksmanager_log_category'] ) ){
            foreach( $_POST['wtgtasksmanager_log_category'] as $categorycriteria){
                $tasksmanager_settings['logsettings']['logscreen']['categorycriteria'][$categorycriteria] = true;                   
            }            
        }         

        // priority criteria
        if( isset( $_POST['wtgtasksmanager_log_priority'] ) ){
            foreach( $_POST['wtgtasksmanager_log_priority'] as $prioritycriteria){
                $tasksmanager_settings['logsettings']['logscreen']['prioritycriteria'][$prioritycriteria] = true;                   
            }            
        }         

        ############################################################
        #         SAVE CUSTOM SEARCH CRITERIA SINGLE VALUES        #
        ############################################################
        // page
        if( isset( $_POST['wtgtasksmanager_pluginpages_logsearch'] ) && $_POST['wtgtasksmanager_pluginpages_logsearch'] != 'notselected' ){
            $tasksmanager_settings['logsettings']['logscreen']['page'] = $_POST['wtgtasksmanager_pluginpages_logsearch'];
        }   
        // action
        if( isset( $_POST['csv2pos_logactions_logsearch'] ) && $_POST['csv2pos_logactions_logsearch'] != 'notselected' ){
            $tasksmanager_settings['logsettings']['logscreen']['action'] = $_POST['csv2pos_logactions_logsearch'];
        }   
        // screen
        if( isset( $_POST['wtgtasksmanager_pluginscreens_logsearch'] ) && $_POST['wtgtasksmanager_pluginscreens_logsearch'] != 'notselected' ){
            $tasksmanager_settings['logsettings']['logscreen']['screen'] = $_POST['wtgtasksmanager_pluginscreens_logsearch'];
        }  
        // line
        if( isset( $_POST['wtgtasksmanager_logcriteria_phpline'] ) ){
            $tasksmanager_settings['logsettings']['logscreen']['line'] = $_POST['wtgtasksmanager_logcriteria_phpline'];
        }  
        // file
        if( isset( $_POST['wtgtasksmanager_logcriteria_phpfile'] ) ){
            $tasksmanager_settings['logsettings']['logscreen']['file'] = $_POST['wtgtasksmanager_logcriteria_phpfile'];
        }          
        // function
        if( isset( $_POST['wtgtasksmanager_logcriteria_phpfunction'] ) ){
            $tasksmanager_settings['logsettings']['logscreen']['function'] = $_POST['wtgtasksmanager_logcriteria_phpfunction'];
        }
        // panel name
        if( isset( $_POST['wtgtasksmanager_logcriteria_panelname'] ) ){
            $tasksmanager_settings['logsettings']['logscreen']['panelname'] = $_POST['wtgtasksmanager_logcriteria_panelname'];
        }
        // IP address
        if( isset( $_POST['wtgtasksmanager_logcriteria_ipaddress'] ) ){
            $tasksmanager_settings['logsettings']['logscreen']['ipaddress'] = $_POST['wtgtasksmanager_logcriteria_ipaddress'];
        }
        // user id
        if( isset( $_POST['wtgtasksmanager_logcriteria_userid'] ) ){
            $tasksmanager_settings['logsettings']['logscreen']['userid'] = $_POST['wtgtasksmanager_logcriteria_userid'];
        }
        
        $this->WTGTASKSMANAGER->update_settings( $tasksmanager_settings );
        $this->UI->n_postresult_depreciated( 'success', __( 'Log Settings Saved', 'wtgtasksmanager' ), __( 'It may take sometime for new log entries to be created depending on your websites activity.', 'wtgtasksmanager' ) );  
    }  
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */       
    public function beginpluginupdate() {
        $this->Updates = $this->WTGTASKSMANAGER->load_class( 'WTGTASKSMANAGER_Formbuilder', 'class-forms.php', 'classes' );
        
        // check if an update method exists, else the plugin needs to do very little
        eval( '$method_exists = method_exists ( $this->Updates , "patch_' . $_POST['wtgtasksmanager_plugin_update_now'] .'" );' );

        if( $method_exists){
            // perform update by calling the request version update procedure
            eval( '$update_result_array = $this->Updates->patch_' . $_POST['wtgtasksmanager_plugin_update_now'] .'( "update");' );       
        }else{
            // default result to true
            $update_result_array['failed'] = false;
        } 
      
        if( $update_result_array['failed'] == true){           
            $this->UI->create_notice( __( 'The update procedure failed, the reason should be displayed below. Please try again unless the notice below indicates not to. If a second attempt fails, please seek support.', 'wtgtasksmanager' ), 'error', 'Small', __( 'Update Failed', 'wtgtasksmanager' ) );    
            $this->UI->create_notice( $update_result_array['failedreason'], 'info', 'Small', 'Update Failed Reason' );
        }else{  
            // storing the current file version will prevent user coming back to the update screen
            global $wtgtasksmanager_currentversion;        
            update_option( 'wtgtasksmanager_installedversion', $wtgtasksmanager_currentversion);

            $this->UI->create_notice( __( 'Good news, the update procedure was complete. If you do not see any errors or any notices indicating a problem was detected it means the procedure worked. Please ensure any new changes suit your needs.', 'wtgtasksmanager' ), 'success', 'Small', __( 'Update Complete', 'wtgtasksmanager' ) );
            
            // do a redirect so that the plugins menu is reloaded
            wp_redirect( get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=wtgtasksmanager' );
            exit;                
        }
    }
    
    /**
    * Save drip feed limits  
    */
    public function schedulerestrictions() {
        $wtgtasksmanager_schedule_array = $this->WTGTASKSMANAGER->get_option_schedule_array();
        
        // if any required values are not in $_POST set them to zero
        if(!isset( $_POST['day'] ) ){
            $wtgtasksmanager_schedule_array['limits']['day'] = 0;        
        }else{
            $wtgtasksmanager_schedule_array['limits']['day'] = $_POST['day'];            
        }
        
        if(!isset( $_POST['hour'] ) ){
            $wtgtasksmanager_schedule_array['limits']['hour'] = 0;
        }else{
            $wtgtasksmanager_schedule_array['limits']['hour'] = $_POST['hour'];            
        }
        
        if(!isset( $_POST['session'] ) ){
            $wtgtasksmanager_schedule_array['limits']['session'] = 0;
        }else{
            $wtgtasksmanager_schedule_array['limits']['session'] = $_POST['session'];            
        }
                                 
        // ensure $wtgtasksmanager_schedule_array is an array, it may be boolean false if schedule has never been set
        if( isset( $wtgtasksmanager_schedule_array ) && is_array( $wtgtasksmanager_schedule_array ) ){
            
            // if times array exists, unset the [times] array
            if( isset( $wtgtasksmanager_schedule_array['days'] ) ){
                unset( $wtgtasksmanager_schedule_array['days'] );    
            }
            
            // if hours array exists, unset the [hours] array
            if( isset( $wtgtasksmanager_schedule_array['hours'] ) ){
                unset( $wtgtasksmanager_schedule_array['hours'] );    
            }
            
        }else{
            // $schedule_array value is not array, this is first time it is being set
            $wtgtasksmanager_schedule_array = array();
        }
        
        // loop through all days and set each one to true or false
        if( isset( $_POST['wtgtasksmanager_scheduleday_list'] ) ){
            foreach( $_POST['wtgtasksmanager_scheduleday_list'] as $key => $submitted_day ){
                $wtgtasksmanager_schedule_array['days'][$submitted_day] = true;        
            }  
        } 
        
        // loop through all hours and add each one to the array, any not in array will not be permitted                              
        if( isset( $_POST['wtgtasksmanager_schedulehour_list'] ) ){
            foreach( $_POST['wtgtasksmanager_schedulehour_list'] as $key => $submitted_hour){
                $wtgtasksmanager_schedule_array['hours'][$submitted_hour] = true;        
            }           
        }    

        if( isset( $_POST['deleteuserswaiting'] ) )
        {
            $wtgtasksmanager_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'enabled';                
        }
        
        if( isset( $_POST['eventsendemails'] ) )
        {
            $wtgtasksmanager_schedule_array['eventtypes']['sendemails']['switch'] = 'enabled';    
        }        
  
        $this->WTGTASKSMANAGER->update_option_schedule_array( $wtgtasksmanager_schedule_array );
        $this->UI->notice_depreciated( __( 'Schedule settings have been saved.', 'wtgtasksmanager' ), 'success', 'Large', __( 'Schedule Times Saved', 'wtgtasksmanager' ) );   
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */       
    public function logsearchoptions() {
        $this->UI->n_postresult_depreciated( 'success', __( 'Log Search Settings Saved', 'wtgtasksmanager' ), __( 'Your selections have an instant effect. Please browse the Log screen for the results of your new search.', 'wtgtasksmanager' ) );                   
    }
 
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */        
    public function defaultcontenttemplate () {        
        $this->UI->create_notice( __( 'Your default content template has been saved. This is a basic template, other advanced options may be available by activating the WTG Tasks Manager Templates custom post type (pro edition only) for managing multiple template designs.' ), 'success', 'Small', __( 'Default Content Template Updated' ) );         
    }
        
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */       
    public function reinstalldatabasetables() {
        $installation = new WTGTASKSMANAGER_Install();
        $installation->reinstalldatabasetables();
        $this->UI->create_notice( 'All tables were re-installed. Please double check the database status list to
        ensure this is correct before using the plugin.', 'success', 'Small', 'Tables Re-Installed' );
    }
     
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */          
    public function globalswitches() {
        global $tasksmanager_settings;
        $tasksmanager_settings['noticesettings']['wpcorestyle'] = $_POST['uinoticestyle'];        
        $tasksmanager_settings['standardsettings']['textspinrespinning'] = $_POST['textspinrespinning'];
        $tasksmanager_settings['standardsettings']['systematicpostupdating'] = $_POST['systematicpostupdating'];
        $tasksmanager_settings['flagsystem']['status'] = $_POST['flagsystemstatus'];
        $tasksmanager_settings['widgetsettings']['dashboardwidgetsswitch'] = $_POST['dashboardwidgetsswitch'];
        $this->WTGTASKSMANAGER->update_settings( $tasksmanager_settings ); 
        $this->UI->create_notice( __( 'Global switches have been updated. These switches can initiate the use of 
        advanced systems. Please monitor your blog and ensure the plugin operates as you expected it to. If
        anything does not appear to work in the way you require please let WebTechGlobal know.' ),
        'success', 'Small', __( 'Global Switches Updated' ) );       
    } 
       
    /**
    * save capability settings for plugins pages
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function pagecapabilitysettings() {
        global $WTGTASKSMANAGER_Menu;
        
        // get the capabilities array from WP core
        $capabilities_array = $this->WPCore->capabilities();

        // get stored capability settings 
        $saved_capability_array = get_option( 'wtgtasksmanager_capabilities' );

        // to ensure no extra values are stored (more menus added to source) loop through page array
        foreach( $WTGTASKSMANAGER_Menu as $key => $page_array ) {
            
            // ensure $_POST value is also in the capabilities array to ensure user has not hacked form, adding their own capabilities
            if( isset( $_POST['pagecap' . $page_array['name'] ] ) && in_array( $_POST['pagecap' . $page_array['name'] ], $capabilities_array ) ) {
                $saved_capability_array['pagecaps'][ $page_array['name'] ] = $_POST['pagecap' . $page_array['name'] ];
            }
                
        }
          
        update_option( 'wtgtasksmanager_capabilities', $saved_capability_array );
         
        $this->UI->create_notice( __( 'Capabilities for this plugins pages have been stored. Due to this being security related I recommend testing before you logout. Ensure that each role only has access to the plugin pages you intend.' ), 'success', 'Small', __( 'Page Capabilities Updated' ) );        
    }
    
    /**
    * Saves the plugins global dashboard widget settings i.e. which to display, what to display, which roles to allow access
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function dashboardwidgetsettings() {
        global $tasksmanager_settings, $WTGTASKSMANAGER_Menu;
       
        foreach( $WTGTASKSMANAGER_Menu as $key => $section_array ) {

            if( isset( $_POST[ $section_array['name'] . 'dashboardwidgetsswitch' ] ) ) {
                $tasksmanager_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'] = $_POST[ $section_array['name'] . 'dashboardwidgetsswitch' ];    
            }
            
            if( isset( $_POST[ $section_array['name'] . 'widgetscapability' ] ) ) {
                $tasksmanager_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'] = $_POST[ $section_array['name'] . 'widgetscapability' ];    
            }

        }

        $this->WTGTASKSMANAGER->update_settings( $tasksmanager_settings );    
        $this->UI->create_notice( __( 'Your dashboard widget settings have been saved. Please check your dashboard to ensure it is configured as required per role.', 'wtgtasksmanager' ), 'success', 'Small', __( 'Settings Saved', 'wtgtasksmanager' ) );         
    }
    
    /**
    * Insert new entry to the webtechglobal_projects table.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function startnewproject() {
        $project_id = $this->WTGTASKSMANAGER->insertproject( $_POST['newprojectname'] );  
        
        if( $project_id === false )
        {
            $this->UI->create_notice( __( 'The project name "' . $_POST['newprojectname'] . '" already exists. No changes have been made.', 'wtgtasksmanager' ), 'error', 'Small', __( 'Project Name Exists', 'wtgtasksmanager' ) );                                                          
            return;
        }
        
        $this->UI->create_notice( __( "The project ID is $project_id and you can begin assigning tasks to it.", 'wtgtasksmanager' ), 'success', 'Small', __( 'Project Created', 'wtgtasksmanager' ) );                                              
    }
    
    /**
    * Handles request to cancel a single task.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function canceltask() {
        // if no task value in URL 
        if( !isset( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Could not cancel a task as no task ID was submitted.", 'wtgtasksmanager' ), 'error', 'Small', __( 'No Task ID', 'wtgtasksmanager' ) );                                               
            return false;    
        }
        
        // if task value not numeric
        if( !is_numeric( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Could not cancel a task because the submitted task ID is not numeric.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Invalid Task ID', 'wtgtasksmanager' ) );                                                           
            return false;    
        }
        
        // ensure task post exists (do not assume ID is a task post it may be another post)
        $task = get_post( $_GET['task'] );
        if( !$task || $task->post_type == 'post_type' ) {
            $this->UI->create_notice( __( "The task ID you submitted does not appear to belong to any tasks.", 'wtgtasksmanager' ), 'warning', 'Small', __( 'Task Does Not Exist', 'wtgtasksmanager' ) );                                                           
            return false;    
        }        
        
        // update task post
        $my_post = array();
        $my_post['ID'] = $_GET['task'];
        $my_post['post_status'] = 'cancelledtask';
        wp_update_post( $my_post );
        
        $this->UI->create_notice( __( "The task with ID " . $_GET['task'] . " has been cancelled.", 'wtgtasksmanager' ), 'success', 'Small', __( 'Task Cancelled', 'wtgtasksmanager' ) );                                                                 
    }
        
    /**
    * Changes a waiting (new) task status to "startedtask".
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function starttask() {
        // if no task value in URL 
        if( !isset( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Cannot begin a task because no task ID has been submitted.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Task ID Required', 'wtgtasksmanager' ) );                                               
            return false;    
        }
        
        // if task value not numeric
        if( !is_numeric( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Your request could not be complete because the submitted task ID is not a number.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Invalid Task ID', 'wtgtasksmanager' ) );                                                           
            return false;    
        }
        
        // ensure task post exists (do not assume ID is a task post it may be another post)
        $task = get_post( $_GET['task'] );
        if( !$task || $task->post_type == 'post_type' ) {
            $this->UI->create_notice( __( "The submitted ID does not match any task.", 'wtgtasksmanager' ), 'warning', 'Small', __( 'Task Does Not Exist', 'wtgtasksmanager' ) );                                                           
            return false;    
        }        
        
        // update task post
        $my_post = array();
        $my_post['ID'] = $_GET['task'];
        $my_post['post_status'] = 'startedtask';
        wp_update_post( $my_post );
        
        $this->UI->create_notice( __( "The task with ID " . $_GET['task'] . " has been started. You will find the task on the Started screen.", 'wtgtasksmanager' ), 'success', 'Small', __( 'Task Begun', 'wtgtasksmanager' ) );                                                                    
    }    
            
    /**
    * Changes a tasks status to "startedtask" from a state other than waiting i.e. closed, cancelled.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function continuetask() {
        // if no task value in URL 
        if( !isset( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Cannot continue task because no task ID has been submitted.", 'wtgtasksmanager' ), 'error', 'Small', __( 'No Task ID', 'wtgtasksmanager' ) );                                               
            return false;    
        }
        
        // if task value not numeric
        if( !is_numeric( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Could not change any task because the submitted task ID is not a number.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Invalid Task ID', 'wtgtasksmanager' ) );                                                           
            return false;    
        }
        
        // ensure task post exists (do not assume ID is a task post it may be another post)
        $task = get_post( $_GET['task'] );
        if( !$task || $task->post_type == 'post_type' ) {
            $this->UI->create_notice( __( "The task ID you submitted does not belong to a task.", 'wtgtasksmanager' ), 'warning', 'Small', __( 'Task Does Not Exist', 'wtgtasksmanager' ) );                                                           
            return false;    
        }        
        
        // update task post
        $my_post = array();
        $my_post['ID'] = $_GET['task'];
        $my_post['post_status'] = 'startedtask';
        wp_update_post( $my_post );
        
        $this->UI->create_notice( __( "The task with ID " . $_GET['task'] . " will be continued. You can find the task on the Started screen.", 'wtgtasksmanager' ), 'success', 'Small', __( 'Task Being Continued', 'wtgtasksmanager' ) );                                                                    
    }    
    
    /**
    * Changes a tasks status to "closedtask"
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function closetask() {
        // if no task value in URL 
        if( !isset( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Cannot close task because no task ID was submitted.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Task ID Required', 'wtgtasksmanager' ) );                                               
            return false;    
        }
        
        // if task value not numeric
        if( !is_numeric( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Could not change a task because the submitted task ID is not numeric.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Invalid Task ID', 'wtgtasksmanager' ) );                                                           
            return false;    
        }
        
        // ensure task post exists (do not assume ID is a task post it may be another post)
        $task = get_post( $_GET['task'] );
        if( !$task || $task->post_type == 'post_type' ) {
            $this->UI->create_notice( __( "The ID you submitted does not belong to a task.", 'wtgtasksmanager' ), 'warning', 'Small', __( 'Task Does Not Exist', 'wtgtasksmanager' ) );                                                           
            return false;    
        }        
        
        // update task post
        $my_post = array();
        $my_post['ID'] = $_GET['task'];
        $my_post['post_status'] = 'closedtask';
        wp_update_post( $my_post );
        
        $this->UI->create_notice( __( "The task with ID " . $_GET['task'] . " has been closed. You can find the task on the Closed screen.", 'wtgtasksmanager' ), 'success', 'Small', __( 'Task Closed', 'wtgtasksmanager' ) );                                                                    
    }
    
    /**
    * Changes a tasks status to "finishedstatus"
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function finishtask() {
        // if no task value in URL 
        if( !isset( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Cannot finish a task because no task ID was provided.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Task ID Required', 'wtgtasksmanager' ) );                                               
            return false;    
        }
        
        // if task value not numeric
        if( !is_numeric( $_GET['task'] ) ) {
            $this->UI->create_notice( __( "Could not change a task because the submitted task ID is not numeric.", 'wtgtasksmanager' ), 'error', 'Small', __( 'Invalid Task ID', 'wtgtasksmanager' ) );                                                           
            return false;    
        }
        
        // ensure task post exists (do not assume ID is a task post it may be another post)
        $task = get_post( $_GET['task'] );
        if( !$task || $task->post_type == 'post_type' ) {
            $this->UI->create_notice( __( "The ID you submitted does not belong to a task.", 'wtgtasksmanager' ), 'warning', 'Small', __( 'Task Does Not Exist', 'wtgtasksmanager' ) );                                                           
            return false;    
        }        
        
        // update task post
        $my_post = array();
        $my_post['ID'] = $_GET['task'];
        $my_post['post_status'] = 'finishedtask';
        wp_update_post( $my_post );
        
        $this->UI->create_notice( __( "The task with ID " . $_GET['task'] . " has been finished. You can find the task on the Finished screen.", 'wtgtasksmanager' ), 'success', 'Small', __( 'Task Finished', 'wtgtasksmanager' ) );                                                                    
    }
    
    /**
    * Creates a standard task
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function advanced() {     
        // set required tasks
        $required = 0;
        if( isset( $_POST['required'] ) && $_POST['required'] ) {
            $required = $_POST['required'];    
        }
        
        if( !isset( $_POST['projectid'] ) || !is_numeric( $_POST['projectid'] ) ) {
            $this->UI->create_notice( __( 'You can create a new project on the main screen. Then you must select it when creating a task.', 'wtgtasksmanager'), 'error', 'Small', __( 'Project Required', 'wtgtasksmanager' ) );                  
            return false;
        }
          
        $new_task_id = $this->WTGTASKSMANAGER->tasknew( $_POST['taskname'], $_POST['taskdescription'], '', get_current_user_id(), $_POST['projectid'], $_POST['priority'], $required, $_POST['freelanceroffer'], $_POST['requiredcapability'] );
        $this->UI->create_notice( sprintf( __( 'Your new tasks ID is %s and you assigned it to project with ID %s.', 'wtgtasksmanager'), $new_task_id, $_POST['projectid'] ), 'success', 'Small', __( 'Task Created', 'wtgtasksmanager' ) );              
    }
    
    /**
    * Import tasks for multiple projects (Projects header) from .csv file.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.2
    * @version 1.1
    */
    public function csvimporttasksmultipleprojects() {
        global $wpdb;

        // handle file upload, create the uploads array
        $uploads = array( 'path' => WP_CONTENT_DIR,
                          'subdir' => '',
                          'error' => false 
        ); 
                
        $file_import_result = $this->Files->singlefile_uploader( $_FILES['taskscsv'], $uploads );
        
        // let the user know it has all gone very wrong and they are DOOMED! 
        if( $file_import_result['outcome'] === false ) {   
            $this->UI->create_notice( $file_import_result['message'], 'error', 'Small', __( 'File Upload Failed', 'wtgtasksmanager' ) );
            return;    
        }
        
        // build array of information for this file (this can be customized to work with multiple files)
        $files_array = array( 'total_files' => 1 );
        $files_array[1]['fullpath'] = $file_import_result['filepath'];
                                                           
        // create success notice regarding file processing 
        $this->UI->create_notice( $file_import_result['message'], 'success', 'Small', __( 'File Transferred', 'wtgtasksmanager' ) );   
                           
        // establish separator
        $files_array[1]['sep'] = $this->Files->established_separator( $file_import_result['filepath'] );
                    
        // read file to get first row as header
        $file = new SplFileObject( $file_import_result['filepath'] );
        while (!$file->eof() ) {
            $header_array = $file->fgetcsv( $files_array[1]['sep'], '"' );
            break;// we just need the first line to do a count()
        }                                             
        unset( $file );
        
        // count number of fields
        $files_array[1]['fields'] = count( $header_array );
        
        // create arrays of original headers and one of sql prepared headers
        foreach( $header_array as $key => $header ){  
            $files_array[1]['originalheaders'][$key] = $header;
            $files_array[1]['sqlheaders'][$key] = $this->PHP->clean_sqlcolumnname( $header );        
        }                                                                   
        
        // ensure all headers are valid
        $cleanedidcolumn = '';
        if( !empty( $id_column ) && is_string( $id_column ) ){
            $cleanedidcolumn = $this->PHP->clean_sqlcolumnname( $id_column );
            if(!in_array( $cleanedidcolumn, $files_array[1]['sqlheaders'] ) ){
                $this->UI->create_notice( 'You entered ' . $id_column . ' as your ID column but it does not match any column header in your .csv file.', 'error', 'Small', __( "Invalid ID Column") );
                return;
            }
        }
        
        // create tasks
        $total_tasks_created = 0;
        $total_tasks_failed = 0;
        
        /*            
            0. Priority
            1. Task Name
            2. Project Name
            3. Short Description
            4. Long Description
            5. Required Task
            6. Notify Email
            7. URL
            8. Code
        */
  
        if( ( $handle = fopen( $file_import_result['filepath'], "r" ) ) !== FALSE ) {
            while ( ( $row = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {
                $projectid = false;

                // set priority
                if( !$row[0] ) { $priority = 3; } else{ $priority = $row[0]; }

                // set project name
                if( !$row[2] ) { $projectname = __( 'Unknown', 'wtgtasksmanager' ); } else { $projectname = $row[2]; }
        
                // set short description
                $shortdescription = $row[3];
                
                // set long (html included) description
                $longdescription = $row[4];
                                
                // we will populate descriptions further: long using short or short using log
                if( empty( $shortdescription ) && is_string( $longdescription ) ) {
                    $shortdescription = $this->PHP->truncate( $longdescription, 150 );
                } 
                if( empty( $longdescription ) && is_string( $shortdescription ) ) {
                    $longdescription = $shortdescription;    
                }
                
                // set task name (must be after descriptions should we need them)
                if( !$row[1] ) { 
                
                    // explode from commonly used : 
                    $arr = explode(":", $shortdescription, 2);
                    
                    // if the first item matches the original then we try another method
                    if( $arr[0] === $shortdescription ) {
                        
                        // create a task name using short description    
                        $taskname = $this->PHP->truncate( $shortdescription, 35 );
                    
                    } else {
                        $taskname = $arr[0];// first item in array becomes task title     
                    }
                
                } else { 
                    $taskname = $row[1]; 
                }
                                
                // set required task
                if( !$row[5] || $row[5] === 0 ) { $required_task = false; } else { $required_task = $row[5]; }
                
                // set notification email address
                if( !$row[6] ) { $notificationemailaddress = false; } else { $notificationemailaddress = $row[6]; }
                
                // set URL
                if( !$row[7] ) { $task_URL = ''; } else { $task_URL = $row[7]; }
                
                // set code sample
                if( !$row[8] ) { $task_code = ''; } else { $task_code = $row[8]; }
                
                // get project ID
                $projectid = $this->WTGTASKSMANAGER->get_projectid_byname( $row[2] );
                
                $new_task_id = $this->WTGTASKSMANAGER->tasknew( $taskname, $longdescription, $shortdescription, get_current_user_id(), $projectid, $priority, $required_task, false, 'activate_plugins', $notificationemailaddress );
                
                if( $new_task_id ) { 
                    ++$total_tasks_created;
                } else {
                    ++$total_tasks_failed;    
                }
            }
            fclose($handle);
        }
    
        // create a new task per row 
        $this->UI->create_notice( sprintf( __( 'A total of %s tasks have been created and %s failed (reasons could vary and require investigation with the help of WebTechGlobal).', 'wtgtasksmanager' ), $total_tasks_created, $total_tasks_failed ), 'success', 'Small', __( 'Data Source Ready' ) );  
    }

    /**
    * Handles request to focus on a specific project.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function projectfocus() {
        if( !isset( $_GET['projectid'] ) )
        {
            $this->UI->create_notice( __( 'To request focus on a specific project you must include a projet ID in your request.' ),'error', 'Small', __( 'Missing Project ID', 'wtgtasksmanager' ) );
            return false;    
        }
        
        if( !is_numeric( $_GET['projectid'] ) ) 
        {
            $this->UI->create_notice( __( 'The project ID you have included in your request does not appear to be valid, please try again.' ),'error', 'Small', __( 'Invalid Project ID', 'wtgtasksmanager' ) );
            return false;    
        }
            
        update_user_meta( get_current_user_id(), 'wtgprojectfocus', $_GET['projectid'] ); 
        
        $this->UI->create_notice( __( 'Your project with ID ' . $_GET['projectid'] . ' is now being focused on. All of the plugins interfaces will hide information about other projects. This does not apply to the custom post type which allows all projects tasks to be viewed as a secondary method.' ),'success', 'Small', __( 'Project Focused', 'wtgtasksmanager' ) );
    }
    
    /**
    * Bulk action request for cancelling tasks.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function canceltasks() {
        $this->UI->create_notice( sprintf( __( 'Just testing. %s  %s.', 'wtgtasksmanager'), '', '' ), 'success', 'Small', __( 'Just A Test', 'wtgtasksmanager' ) );                  
    }
 
    /**
    * Called when a new task is created (Edit Post). 
    * 
    * Nonce, WTG security and WTG validation is done prior to this being called.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function wtgtasksmainoptions() {
        $this->WTGTASKSMANAGER->task_meta( $_POST['post_ID'], $_POST['projectid'], $_POST['priority'], $_POST['requiredtasks'], $_POST['freelanceroffer'], $_POST['requiredcapability'], false );
    }

    /**
    * Debug mode switch.
    * 
    * @author Ryan R. Bayne
    * @package WebTechGlobal WordPress Plugins
    * @version 1.0
    */
    public function debugmodeswitch() {
        $debug_status = get_option( 'webtechglobal_displayerrors' );
        if($debug_status){
            update_option( 'webtechglobal_displayerrors',false );
            $new = 'disabled';
            
            $this->UI->create_notice( __( "Error display mode has been $new." ), 'success', 'Tiny', __( 'Debug Mode Switch', 'wtgtasksmanager' ) );               
                        
            wp_redirect( get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=' . $_GET['page'] );
            exit;
        } else {
            update_option( 'webtechglobal_displayerrors',true );
            $new = 'enabled';
            
            $this->UI->create_notice( __( "Error display mode has been $new." ), 'success', 'Tiny', __( 'Debug Mode Switch', 'wtgtasksmanager' ) );               
            
            wp_redirect( get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=' . $_GET['page'] );
            exit;
        }
    }
               
}// WTGTASKSMANAGER_Requests       
?>